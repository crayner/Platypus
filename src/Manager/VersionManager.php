<?php
/**
 * Created by PhpStorm.
 *
 * This file is part of the Busybee Project.
 *
 * (c) Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * User: craig
 * Date: 8/06/2018
 * Time: 16:44
 */

namespace App\Manager;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;

class VersionManager
{
    /**
     * @var string
     */
    const VERSION = '0.1.03';

    /**
     * System Requirements
     */
    CONST SYSTEM_REQUIREMENTS = [
        'php' 			=> '7.2',
        'mysql' 		=> '5.7',
        'mariadb' 		=> '10.2',
        'extensions' 	=> ['gettext', 'mbstring', 'curl', 'zip', 'xml', 'gd'],
        'settings' 		=> [
            ['max_input_vars', '>=', 5000],
            ['max_file_uploads', '>=', 20],
            ['allow_url_fopen', '==', 1],
            ['register_globals', '==', 0],
        ]
    ];

    /**
     * @param $version
     *
     * @return string
     */
    public static function incrementVersion($version)
    {
        $parts = explode('.', $version);
        if (count($parts) !== 3)
        {
            trigger_error('This process only accepts standard 3 part versions. (0.0.00)');
            return $version;
        }

        if ($parts[2] + 1 < 99) {
            $parts[2]++;
        } else {
            $parts[2] = 0;
            if ($parts[1] + 1 <= 99) {
                $parts[1]++;
            } else {
                $parts[1] = 0;
                $parts[0]++;
            }
        }

        $parts[2] = str_pad($parts[2], 2, '0', STR_PAD_LEFT);

        return implode('.', $parts);
    }

    /**
     * systemCheck
     *
     * @return array
     */
    public function systemCheck()
    {
        $sections = [];

        $section['header'] = 'system_requirements';

        $check['label'] = 'php';
        $check['requirement'] = VersionManager::SYSTEM_REQUIREMENTS['php'];
        $check['value'] = phpversion();
        $check['ok'] = version_compare($check['value'], $check['requirement'], '>=');
        $section['checks'][] = $check;

        $check['label'] = 'mysql';
        $check['requirement'] = VersionManager::SYSTEM_REQUIREMENTS['mysql'];
        $result = $this->getEntityManager()->getConnection()->prepare('SELECT VERSION()');
        $result->execute();
        $result = $result->fetchAll(\PDO::FETCH_COLUMN);
        $check['value'] = $result[0];
        if (strpos($check['value'], 'MariaDB') !== false)
        {
            $check['label'] = 'mariadb';
            $check['requirement'] = VersionManager::SYSTEM_REQUIREMENTS['mariadb'];
        }

        $check['ok'] = version_compare($check['value'], $check['requirement'], '>=');
        $section['checks'][] = $check;

        $check['label'] = 'mysql_collation';
        $check['requirement'] = 'utf8mb4_general_ci';
        $result = $this->getEntityManager()->getConnection()->prepare('SELECT COLLATION(\''.$this->getSettingManager()->getParameter('db_name').'\')');
        $result->execute();
        $result = $result->fetchAll(\PDO::FETCH_COLUMN);
        $check['value'] = $result[0];

        $check['ok'] = ($check['value'] === $check['requirement']);
        $section['checks'][] = $check;

        $check['label'] = 'mysql_pdo';
        $check['requirement'] = '';
        $check['value'] = '';

        $check['ok'] = (@extension_loaded('pdo_mysql'));
        $section['checks'][] = $check;

        $sections[] = $section;

        $section['header'] = 'php_extensions';
        $section['checks'] = [];
        foreach(VersionManager::SYSTEM_REQUIREMENTS['extensions'] as $extension) {
            $check['label'] = 'extension.' . $extension;
            $check['requirement'] = '';
            $check['value'] = '';

            $check['ok'] = (@extension_loaded($extension));
            $section['checks'][] = $check;
        }
        $sections[] = $section;

        $section['header'] = 'php_settings';
        $section['checks'] = [];
        foreach(VersionManager::SYSTEM_REQUIREMENTS['settings'] as $extension) {
            $check['label'] = 'setting.' . $extension[0];
            $check['requirement'] = $extension[1] . ' ' . $extension[2];
            $check['value'] = @ini_get($extension[0]);

            $check['ok'] = ($extension[1] == '==' && $check['value'] == $extension[2]) ? true :
                ($extension[1] == '>=' && $check['value'] >= $extension[2]) ? true :
                    ($extension[1] == '<=' && $check['value'] <= $extension[2]) ? true :
                        ($extension[1] == '>' && $check['value'] > $extension[2]) ? true :
                            ($extension[1] == '<' && $check['value'] < $extension[2]) ? true :
                                false;

            $section['checks'][] = $check;
        }
        $sections[] = $section;

        $section['header'] = 'file_permissions';
        $section['checks'] = [];

        $fileCount = 0;
        $publicWriteCount = 0;
        $path = $this->getSettingManager()->getParameter('kernel.project_dir');
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path.DIRECTORY_SEPARATOR.'src')) as $filename)
        {
            if (pathinfo($filename, PATHINFO_EXTENSION) != 'php') continue;

            $fileCount++;
            if (fileperms($filename) & 0x0002) $publicWriteCount++;
        }
        $check['label'] = 'file_not_write';
        $check['requirement'] = '';
        $check['value'] = $fileCount.'('.$publicWriteCount.')';

        $check['ok'] = $publicWriteCount === 0 ? true : false;

        $section['checks'][] = $check;

        $fileCount = 0;
        $publicWriteCount = 0;
        $path = $this->getSettingManager()->getParameter('kernel.project_dir');
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'uploads')) as $filename)
        {
            if (is_dir($filename)) continue;
            $fileCount++;
            if (is_writable($filename))
                $publicWriteCount++;
        }
        $fileCount++;
        if (is_writable($path.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'uploads'))
            $publicWriteCount++;
        $check['label'] = 'uploads_write';
        $check['requirement'] = '';
        $check['value'] = $fileCount.'('.$publicWriteCount.')';

        $check['ok'] = $fileCount - $publicWriteCount === 0 ? true : false;

        $section['checks'][] = $check;

        $fileCount = 0;
        $publicWriteCount = 0;
        $path = $this->getSettingManager()->getParameter('kernel.project_dir');
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path.DIRECTORY_SEPARATOR.'config')) as $filename)
        {
            if (is_dir($filename)) continue;
            $fileCount++;
            if (is_writable($filename))
                $publicWriteCount++;
        }
        $fileCount++;
        if (is_writable($path.DIRECTORY_SEPARATOR.'config'))
            $publicWriteCount++;
        $check['label'] = 'config_write';
        $check['requirement'] = '';
        $check['value'] = $fileCount.'('.$publicWriteCount.')';

        $check['ok'] = $fileCount - $publicWriteCount === 0 ? true : false;

        $section['checks'][] = $check;
        $sections[] = $section;

        return $sections;
    }

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @return EntityManager
     */
    public function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @return VersionManager
     */
    public function setEntityManager(EntityManagerInterface $entityManager): VersionManager
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    /**
     * @var SettingManager
     */
    private $settingManager;

    /**
     * @return SettingManager
     */
    public function getSettingManager(): SettingManager
    {
        return $this->settingManager;
    }

    /**
     * @param SettingManager $settingManager
     * @return VersionManager
     */
    public function setSettingManager(SettingManager $settingManager): VersionManager
    {
        $this->settingManager = $settingManager;
        return $this;
    }
}