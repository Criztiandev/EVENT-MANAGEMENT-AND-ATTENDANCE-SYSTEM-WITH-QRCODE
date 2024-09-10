<?php
require from("views/helper/partials/head.partials.php");

?>


<main class="">

    <div class="flex gap-3">
        <span class="font-bold">Name of the event:</span>
        <span class="font-bold"><?= $details["NAME"] ?></span>
    </div>

    <div class="flex gap-3">
        <span class="font-bold"> Year and Section:</span>
        <span class="font-bold"><?= $details["COURSE_NAME"] ?></span>
    </div>

    <div class="flex gap-3">
        <span class="font-bold"> Department:</span>
        <span class="font-bold"><?= $details["DEPARTMENT_NAME"] ?></span>
    </div>

    <div class="flex gap-3">
        <span class="font-bold"> Organization:</span>
        <span class="font-bold"><?= $details["ORGANIZATION_NAME"] ?></span>
    </div>

    <div class="flex gap-3 mb-4">
        <span class="font-bold"> Date and Time: </span>
        <span class="font-bold"><?= $details["START_DATE"] ?> - <?= $details["END_DATE"] ?>
            (<?= $details["START_TIME"] ?> - <?= $details["END_TIME"] ?>) AM
        </span>
    </div>

    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 border border-black">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="border border-black px-4 py-3">Student ID</th>
                <th scope="col" class="border border-black px-4 py-3">Name</th>
                <th scope="col" class="border border-black px-4 py-3">Course</th>
                <th scope="col" class="border border-black px-4 py-3">Department</th>
                <th scope="col" class="border border-black px-4 py-3">Year level</th>
                <th scope="col" class="border border-black px-4 py-3">Time in (AM)</th>
                <th scope="col" class="border border-black px-4 py-3">Time out (AM)</th>
                <th scope="col" class="border border-black px-4 py-3">Time in (PM)</th>
                <th scope="col" class="border border-black px-4 py-3">Time out (PM)</th>

            </tr>
        </thead>
        <tbody>
            <?php foreach ($attendees_list as $items): ?>
                <tr class="border-b dark:border-gray-700">
                    <th scope="row"
                        class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white border border-black">
                        <?= $items["STUDENT_ID"] ?>
                    </th>

                    <th scope="row"
                        class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white border border-black">
                        <?= $items["FULLNAME"] ?>
                    </th>

                    <td class="px-4 py-3 border border-black">
                        <?= $items["COURSE_NAME"] ?>
                    </td>
                    <td class="px-4 py-3 border border-black">
                        <?= $items["DEPARTMENT_NAME"] ?>
                    </td>
                    <td class="px-4 py-3 border border-black">
                        <?= $items["YEAR_LEVEL"] ?>
                    </td>

                    <td class="px-4 py-3 border border-black">
                        <?= !empty($items["CHECK_IN_TIME_AM"]) ? htmlspecialchars($items["CHECK_IN_TIME_AM"]) : 'Absent'; ?>
                    </td>
                    <td class="px-4 py-3 border border-black">
                        <?= !empty($items["CHECK_OUT_TIME_AM"]) ? htmlspecialchars($items["CHECK_OUT_TIME_AM"]) : 'Absent'; ?>
                    </td>
                    <td class="px-4 py-3 border border-black">
                        <?= !empty($items["CHECK_IN_TIME_PM"]) ? htmlspecialchars($items["CHECK_IN_TIME_PM"]) : 'Absent'; ?>
                    </td>
                    <td class="px-4 py-3 border border-black">
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