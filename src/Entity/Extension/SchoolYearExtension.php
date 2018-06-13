<?php
namespace App\Entity\Extension;

use App\Entity\SchoolYear;

abstract class SchoolYearExtension
{
	/**
	 * Can Delete
	 *
	 * @return bool
	 */
	public function canDelete()
	{
		if (!empty($this->getSpecialDays()))
			foreach ($this->getSpecialDays()->toArray() as $specialDay)
				if (!$specialDay->canDelete())
					return false;
        if (!empty($this->getTerms()))
            foreach ($this->getTerms()->toArray() as $term)
                if (!$term->canDelete())
                    return false;
		if (!empty($this->getTerms()) && !empty($this->getSpecialDays()))
			return false;

		return true;
	}

	/**
	 * @param SchoolYear $calendar
	 *
	 * @return bool
	 */
	public function isEqual(SchoolYear $schoolYear): bool
	{
        if ($this->getId() !== $schoolYear->getId())
            return false;

        return true;
	}
}