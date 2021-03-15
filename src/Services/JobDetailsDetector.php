<?php

namespace App\Services;

use App\Entity\Job;

class JobDetailsDetector
{
    private const JUNIOR_KEYWORDS = [
        'student',
        'intern',
        'internship',
        'mentorship',
        'apprenticeship',
        'junior',
        'traineeship',
        'trainee',
    ];

    private const EXPERT_KEYWORDS = [
        'expert',
        'experienced',
        'experience',
        'skilled',
        'professional',
        'senior',
        'tech lead',
        'mentor',
        'leader',
        'lead',
        'manager'
    ];

    private const SENTENCE_WITH_YEARS_OF_EXPERIENCE_REGEXP = '/[A-Z][a-z\s]*(\d).?(\d)?[\w\s,\\-]*experience[\w\s,\\-]*\./m';

    /**
     * @param Job $job
     * @return Job
     */
    public function detectJobDetails(Job $job): Job
    {
        /** @var string $level */
        $level = $this->guessLevel($job);

        /** @var int|null $yearsOfExperience */
        $yearsOfExperience = (int)$this->guessYearsOfExperience($job);

        $job->setLevel($level);
        $job->setYearsOfExperience($yearsOfExperience);

        return $job;
    }

    /**
     * @param Job $job
     * @return string|null
     */
    private function guessLevel(Job $job): string
    {
        $level = Job::LEVEL_REGULAR;

        if ($this->isSeniorPosition($job)) {
            $level = Job::LEVEL_SENIOR;
        }

        if ($this->isJuniorPosition($job)) {
            $level = Job::LEVEL_JUNIOR;
        }

        return $level;
    }

    private function isJuniorPosition(Job $job): bool
    {
        $found = [];

        foreach (self::JUNIOR_KEYWORDS as $keyword) {
            if (strpos(mb_strtolower($job->getTitle()), $keyword) !== false) {
                $found[] = $keyword;
            }
        }

        return !empty($found);
    }


    private function isSeniorPosition(Job $job): bool
    {
        $found = [];

        foreach (self::EXPERT_KEYWORDS as $keyword) {
            if (strpos(mb_strtolower($job->getTitle()), $keyword) !== false) {
                $found[] = $keyword;
            }
        }

        return !empty($found);
    }

    /**
     * @param Job $job
     * @param string|null $level
     * @return int|null
     */
    private function guessYearsOfExperience(Job $job): ?int
    {
        preg_match(self::SENTENCE_WITH_YEARS_OF_EXPERIENCE_REGEXP, $job->getRequirements(), $matches);

        return !empty($matches) ? $matches[1] : 0;
    }
}