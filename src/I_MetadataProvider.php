<?php
namespace Throup\GrabRadio;

interface I_MetadataProvider {
    public function getBrand();

    public function getDate();

    public function getDescription();

    public function getEpisode();

    public function getProgramme();

    public function getSeries();

    public function getTitle();

    public function getTotal();
}
