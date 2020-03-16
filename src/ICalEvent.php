<?php

namespace Timegridio\ICalReader;

use Carbon\Carbon;
use Illuminate\Support\Arr;

class ICalEvent
{
    private $start;

    private $end;

    private $busy = true;

    private $summary = '';

    public function __construct($data, $timezone)
    {
        $this->init($data, $timezone);
    }

    protected function init($data, $timezone)
    {
        $this->setStart(
            Arr::get($data, 'DTSTART'),
            $timezone
        );

        $this->setEnd(
            Arr::get($data, 'DTEND'),
            $timezone
        );

        $this->setBusy(
            Arr::get($data, 'SUMMARY')
        );

        $this->setSummary(
            Arr::get($data, 'SUMMARY')
        );
    }

    public function getStart()
    {
        return $this->start;
    }

    public function getEnd()
    {
        return $this->end;
    }

    protected function setStart($datetime, $timezone)
    {
        $this->start = $this->makeTimestamp($datetime, $timezone);
    }

    protected function setEnd($datetime, $timezone)
    {
        $this->end = $this->makeTimestamp($datetime, $timezone);
    }

    protected function makeTimestamp($datetime, $timezone)
    {
        return Carbon::parse($datetime)->timezone($timezone);
    }

    protected function setBusy($status)
    {
        $this->busy = (strtolower($status) != 'free');
    }

    public function isBusy()
    {
        return $this->busy;
    }

    public function holds(Carbon $atDatetime)
    {
        return $this->start->lt($atDatetime) && $this->end->gt($atDatetime);
    }

    protected function setSummary(string $summary = null)
    {
        if (!\is_null($summary)) {
            $this->summary = trim($summary);
        }
    }

    public function getSummary(): string
    {
        return $this->summary;
    }
}
