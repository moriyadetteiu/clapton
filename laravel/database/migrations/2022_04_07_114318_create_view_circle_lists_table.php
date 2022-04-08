<?php

use Illuminate\Database\Migrations\Migration;

class CreateViewCircleListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // note: CONCAT(care_about_circles.id, '_', IFNULL(want_circle_products.id, '')) as `id`
        //       の部分はIDが存在しないと、lighthouseのbelongToディレクティブがうまく動作しないため、一意に定まるIDの組み合わせで定義している
        DB::statement("CREATE OR REPLACE
            ALGORITHM = MERGE
            VIEW view_circle_lists
            AS SELECT
                CONCAT(care_about_circles.id, '_', IFNULL(want_circle_products.id, '')) as `id`,
                care_about_circles.id as `care_about_circle_id`,
                circle_placements.id as `circle_placements_id`,
                circle_placement_classifications.id as `circle_placement_classification_id`,
                event_dates.id as `event_date_id`,
                circles.id as `circle_id`,
                join_events.id as `join_event_id`,
                join_events.event_id as `event_id`,
                join_events.team_id as `team_id`,
                users.id as `user_id`,
                want_circle_products.id as `want_circle_product_id`,
                want_priorities.id as `want_priority_id`,
                circle_products.id as `circle_product_id`,
                circle_product_classifications.id as `circle_product_classification_id`,
                event_dates.name as `event_date_name`,
                circle_placements.hole as `placement_hole`,
                circle_placements.line as `placement_line`,
                circle_placements.number as `placement_number`,
                circle_placements.a_or_b as `placement_a_or_b`,
                circles.name as `circle_name`,
                circle_placement_classifications.name as `circle_placement_classification_name`,
                circle_product_classifications.name as `circle_product_classification_name`,
                circle_products.name as `circle_product_name`,
                circle_products.price as `circle_product_price`,
                want_circle_products.quantity as `want_circle_product_quantity`,
                want_priorities.name as `want_priority_name`,
                circles.memo as `circle_memo`,
                users.name as `user_name`
            FROM
                care_about_circles
            INNER JOIN
                circle_placements ON circle_placements.id = care_about_circles.circle_placement_id
            INNER JOIN
                circle_placement_classifications ON circle_placements.circle_placement_classification_id = circle_placement_classifications.id
            INNER JOIN
            	event_dates ON circle_placements.event_date_id = event_dates.id
            INNER JOIN
                circles ON circle_placements.circle_id = circles.id
            INNER JOIN
                join_events ON care_about_circles.join_event_id = join_events.id
            INNER JOIN
                users ON join_events.user_id = users.id
            LEFT JOIN
                want_circle_products ON care_about_circles.id = want_circle_products.care_about_circle_id
            LEFT JOIN
                want_priorities ON want_circle_products.want_priority_id = want_priorities.id
            LEFT JOIN
                circle_products ON want_circle_products.circle_product_id = circle_products.id
            LEFT JOIN
                circle_product_classifications ON circle_products.circle_product_classification_id = circle_product_classifications.id
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS view_circle_lists');
    }
}
