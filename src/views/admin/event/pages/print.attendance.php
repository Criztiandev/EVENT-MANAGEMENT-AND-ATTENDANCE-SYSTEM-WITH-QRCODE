<?php
require from("views/helper/partials/head.partials.php");
// require from("views/helper/partials/navbar.partials.php");
// require from("views/helper/partials/sidebar.partials.php");

?>


<main class="">
<table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                            <th scope="col" class="px-4 py-3">Student ID</th>
                                <th scope="col" class="px-4 py-3">Name</th>
                                <th scope="col" class="px-4 py-3">Course</th>
                                <th scope="col" class="px-4 py-3">Department</th>
                                <th scope="col" class="px-4 py-3">Year level</th>
                                <th scope="col" class="px-4 py-3">Time in (AM)</th>
                                <th scope="col" class="px-4 py-3">Time out (AM)</th>
                                <th scope="col" class="px-4 py-3">Time in (PM)</th>
                                <th scope="col" class="px-4 py-3">Time out (PM)</th>
                             
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($attendees_list as $items): ?>
                                <tr class="border-b dark:border-gray-700">
                                <th scope="row"
                                        class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <?= $items["STUDENT_ID"] ?>
                                    </th>
                                    
                                    <th scope="row"
                                        class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <?= $items["FULLNAME"] ?>
                                    </th>
                                 
                                    <td class="px-4 py-3">
                                        <?= $items["COURSE_NAME"] ?>
                                    </td>
                                    <td class="px-4 py-3">
                                        <?= $items["DEPARTMENT_NAME"]?>
                                    </td>
                                    <td class="px-4 py-3">
                                        <?= $items["YEAR_LEVEL"] ?>
                                    </td>

                                    <td class="px-4 py-3">
                                        <?= !empty($items["CHECK_IN_TIME_AM"]) ? htmlspecialchars($items["CHECK_IN_TIME_AM"]) : 'Absent'; ?>
                                    </td>
                                    <td class="px-4 py-3">
                                        <?= !empty($items["CHECK_OUT_TIME_AM"]) ? htmlspecialchars($items["CHECK_OUT_TIME_AM"]) : 'Absent'; ?>
                                    </td>
                                    <td class="px-4 py-3">
                                        <?= !empty($items["CHECK_IN_TIME_PM"]) ? htmlspecialchars($items["CHECK_IN_TIME_PM"]) : 'Absent'; ?>
                                    </td>
                                    <td class="px-4 py-3">
                                    <?= !empty($items["CHECK_OUT_TIME_PM"]) ? htmlspecialchars($items["CHECK_OUT_TIME_PM"]) : 'Absent'; ?>
                                    </td>
                                  
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
</main>

<?php display("views/helper/components/ui/DeleteModal.php", ["route" => "/event/delete"]) ?>

<!-- Script -->
<?php require from("views/helper/components/script/response.script.php"); ?>
<?php require from("views/helper/partials/footer.partials.php"); ?>