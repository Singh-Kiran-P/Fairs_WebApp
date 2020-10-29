<?php
class Fair_Model
{
  private $m_fair_id;
  private $m_city_id;
  private $m_title;
  private $m_description;
  private $m_start_date;
  private $m_end_date;
  private $m_opening_hour;
  private $m_closing_hour;
  private $m_location;
  private $m_tot_Img;

  public function setVar($fair_id, $city_id, $title, $description, $start_date, $end_date, $opening_hour, $closing_hour, $location, $tot_Img)
  {
    $this->m_fair_id = $fair_id;
    $this->m_city_id = $city_id;
    $this->m_title =  $title;
    $this->m_description =  $description;
    $this->m_start_date = $start_date;
    $this->m_end_date = $end_date;
    $this->m_opening_hour = $opening_hour;
    $this->m_closing_hour = $closing_hour;
    $this->m_location = $location;
    $this->m_tot_Img = $tot_Img;
  }

  public function getVar()
  {
    $out = [
      ["fair_id" => $this->m_fair_id],
      ["city_id" => $this->m_city_id],
      ["title" => $this->m_title],
      ["description" => $this->m_description],
      ["start_date" => $this->m_start_date],
      ["end_date" => $this->m_end_date],
      ["opening_hour" => $this->m_opening_hour],
      ["closing_hour" => $this->m_closing_hour],
      ["location" => $this->m_location],
      ["tot_Img" => $this->m_tot_Img],
    ];

    return $out;
  }
}
