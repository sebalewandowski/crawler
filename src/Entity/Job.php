<?php

namespace App\Entity;

class Job
{
    const LEVEL_JUNIOR = 'junior';
    const LEVEL_REGULAR = 'regular';
    const LEVEL_SENIOR = 'senior';

    /** @var string */
    private string $title = '';

    /** @var string */
    private string $url = '';

    /** @var string */
    private string $category = '';

    /** @var string */
    private string $subCategory = '';

    /** @var string */
    private string $description = '';

    /** @var string */
    private string $requirements = '';

    /** @var string */
    private string $level = '';

    /** @var int|null */
    private ?int $yearsOfExperience = null;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @param string $category
     */
    public function setCategory(string $category): void
    {
        $this->category = $category;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getLevel(): string
    {
        return $this->level;
    }

    /**
     * @param string $level
     */
    public function setLevel(string $level): void
    {
        $this->level = $level;
    }

    /**
     * @return int|null
     */
    public function getYearsOfExperience(): ?int
    {
        return $this->yearsOfExperience;
    }

    /**
     * @param int|null $yearsOfExperience
     */
    public function setYearsOfExperience(?int $yearsOfExperience): void
    {
        $this->yearsOfExperience = $yearsOfExperience;
    }

    /**
     * @return string
     */
    public function getSubCategory(): string
    {
        return $this->subCategory;
    }

    /**
     * @param string $subCategory
     */
    public function setSubCategory(string $subCategory): void
    {
        $this->subCategory = $subCategory;
    }

    /**
     * @return string
     */
    public function getRequirements(): string
    {
        return $this->requirements;
    }

    /**
     * @param string $requirements
     */
    public function setRequirements(string $requirements): void
    {
        $this->requirements = $requirements;
    }
}