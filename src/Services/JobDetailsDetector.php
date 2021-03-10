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
    ];

    private const YEARS_OF_EXPERIENCE_KEYWORDS = [
        1 => "1",
        2 => "1+",
        3 => "2",
        4 => "2+",
        5 => "3",
        6 => "3+",
        7 => "4",
        8 => "4+",
        9 => "5",
        10 => "5+",
    ];

    /**
     * @param Job $job
     * @return Job
     */
    public function detectJobDetails(Job $job): Job
    {
        /** @var string|null $level */
        $level = $this->guessLevel($job);

        /** @var int|null $yearsOfExperience */
        $yearsOfExperience = $this->guessYearsOfExperience($job, $level);

        if (
            !$level
            && $yearsOfExperience !== null
            && $yearsOfExperience <= Job::YEARS_OF_EXPERIENCE_JUNIOR
        ) {
            $level = Job::LEVEL_JUNIOR;
        }


        $job->setLevel($level ?: Job::LEVEL_EXPERT);
        $job->setYearsOfExperience($yearsOfExperience);

        return $job;
    }

    /**
     * @param Job $job
     * @return string|null
     */
    private function guessLevel(Job $job): ?string
    {
        $isJunior = $this->isLevelMatching($job, self::JUNIOR_KEYWORDS);

        if ($isJunior) {
            return Job::LEVEL_JUNIOR;
        }

        return $this->isLevelMatching($job, self::EXPERT_KEYWORDS) ? job::LEVEL_EXPERT : null;
    }

    /**
     * @param Job $job
     * @param string|null $level
     * @return int|null
     */
    private function guessYearsOfExperience(Job $job, string $level = null): ?int
    {
        $yearsOfExp = '';

        if ($job->getUrl() == "senior-data-protection-project-manager-remote-eligible-emea") {
            foreach (self::YEARS_OF_EXPERIENCE_KEYWORDS as $intValue => $stringValue) {
                if (preg_match("/\b(\d-\d+\+) ?\b/i", $job->getRequirements(), $matches)) {
                    if (count($matches) > 1) {
                        $yearsOfExp = $matches[0] . 'years';
                    }
                    return $yearsOfExp;
                }
            }
        }


        if ($level === Job::LEVEL_JUNIOR) {
            return Job::YEARS_OF_EXPERIENCE_JUNIOR;
        }

        if ($level === Job::LEVEL_EXPERT) {
            return Job::YEARS_OF_EXPERIENCE_EXPERT;
        }

        return null;
    }

    /**
     * @param $str
     * @return int|null
     */
    private function filterOutIntValue($str): ?int
    {
        preg_match('!\d+!', $str, $matches);

        return $matches ? (int)array_shift($matches) : null;
    }

    /**
     * @param Job $job
     * @param array $keywords
     * @return bool
     */
    private function isLevelMatching(Job $job, array $keywords): bool
    {
        $matchesInDescription = 0;

//        if ($job->getTitle() == 'Senior Backend Engineer - Freemium (Remote Eligible - EMEA)') {
//
//        }
        foreach ($keywords as $keyword) {
            $pattern = sprintf("/\b%ss?\b/i", $keyword);
            $matchesInTitle = preg_match($pattern, $job->getTitle());


            $matchesInCategory = preg_match_all($pattern, $job->getCategory());
            $matchesInDescription += preg_match_all($pattern, $job->getRequirements());
//            dd($matchesInTitle, $matchesInDescription, $matchesInCategory);
            // At least two matches need in description, because maybe one of the roles is mentoring juniors and etc.
            if (
                $matchesInTitle > 0
                || $matchesInCategory > 0
                || $matchesInDescription >= 2
            ) {
                return true;
            }
        }

        return false;
    }
}