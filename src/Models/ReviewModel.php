<?php
namespace Vanier\Api\Models;

class ReviewModel extends BaseModel {

    private $table_name = "review";

    /**
     * A model class for the `reviews` database table.
     * It exposes operations that can be performed on artists records.
     */
    function __construct() {
        // Call the parent class and initialize the database connection settings.
        parent::__construct();
    }

    /**
     * Get all review records.
     */
    function getAll() {
        $sql = "SELECT * FROM $this->table_name";
        $result = $this->paginate($sql);
        return $result;
    }
    

    /**
     * Get all review records for a specified user
     */
    function getUserReviews($user_id){
        $sql = "SELECT * FROM $this->table_name WHERE user_id = ?";
        $result = $this->paginate($sql, [$user_id]);
        return $result;
    }

   /**
    * Get a review record by its id
    */
    function getReviewById($review_id){
        $sql = "SELECT * FROM $this->table_name WHERE review_id = ?";
        $result = $this->run($sql, [$review_id])->fetch();
        return $result;
    }



    /**
     * Get all review records whose star rating matches the specified value
     */
    function getReviewsByRate($rate){
        $sql = "SELECT * FROM $this->table_name t INNER JOIN cars c ON t.car_id=c.car_id WHERE t.star_rating = :star_rating";
       
        // $sql = "SELECT * FROM $this->table_name 
        //         WHERE star_rating = :star_rating";
        $result = $this->paginate($sql, 
        [ 
            "star_rating" => $rate
        ]);
        return $result;
    }



    /**
     * Get all review records before the specified date
     */
    function getReviewsBeforeYear($date){
        $sql = "SELECT * FROM $this->table_name t INNER JOIN cars c ON t.car_id=c.car_id WHERE c.year <= ?";
        $result = $this->paginate($sql, [$date]);
        return $result;
    }

    /**
     * Get all review records after the specified date
     */
    function getReviewsAfterYear($date){
        $sql = "SELECT * FROM $this->table_name t INNER JOIN cars c ON t.car_id=c.car_id WHERE c.year >= ?";
        $result = $this->paginate($sql, [$date]);
        return $result;
    }

    function getReviewsBetweenYears($date_from,$date_to){
        $sql = "SELECT * FROM $this->table_name t INNER JOIN cars c ON t.car_id=c.car_id WHERE c.year >= ? AND c.year <= ?";
        $result = $this->paginate($sql, [$date_from,$date_to]);
        return $result;
    }

    /**
     * Get all review records on the specified date
     */
    function getReviewsOfYear($date){
        $sql = "SELECT * FROM $this->table_name t INNER JOIN cars c ON t.car_id=c.car_id WHERE c.year LIKE ?";
        $result = $this->paginate($sql, [$date . "%"]);
        return $result;
    }

    function getReviewsBeforeDate($date){
        $sql = "SELECT * FROM $this->table_name 
                WHERE date <= ?";
        $result = $this->paginate($sql, [$date]);
        return $result;
    }

    /**
     * Get all review records after the specified date
     */
    function getReviewsAfterDate($date){
        $sql = "SELECT * FROM $this->table_name 
                WHERE date >= ?";
        $result = $this->paginate($sql, [$date]);
        return $result;
    }

    /**
     * Get all review records on the specified date
     */
    function getReviewsOnDate($date){
        $sql = "SELECT * FROM $this->table_name 
                WHERE date Like ?";
        $result = $this->paginate($sql, [$date . "%"]);
        return $result;
    }

    /**
     * Create one or more review 
     */
    function createReview($review) {
        $data = $this->insert($this->table_name, $review) ;
        return $data;
    }

    /**
     * Update a review record.
     */
    public function updateReview($review, $review_id) {
        $review = $this->update('review', $review, array('review_id' => $review_id));
        return $review;
    }
    
    /**
     * Delete one or more review
     */
    function deleteReview($review_id){
        $data = $this->deleteByIds($this->table_name, "review_id", $review_id);
        return $data;
    }
}
