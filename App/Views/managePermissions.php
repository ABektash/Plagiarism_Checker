<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css'>
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/managePermissions.css">
    <title>Manage Permissions - Plagiarism Detection</title>

</head>

<body>
    <?php include 'inc/sidebar.php'; ?>

    <section id="content">
        <?php include 'inc/navbar.php'; ?>

        <main>
            <div class="head-title">
                <h1>Manage Permissions</h1>
            </div>
            <form class="form-container" action="<?php url('managePermissions/submit'); ?>" method="post" onsubmit="selectAllChosenPages()">
                <input type="hidden" name="userTypeID" id="userTypeID" value="4">
                <table>
                    <tr>
                        <th>Available Pages</th>
                        <th>
                            <select id="user-type-filter" onclick="handleUserTypeChange()">
                                <option value="4">Visitor</option>
                                <option value="1">Admin</option>
                                <option value="2">Instructor</option>
                                <option value="3">Student</option>
                            </select>
                        </th>
                        <th>Chosen Pages</th>
                    </tr>
                    <tr>
                        <td>
                            <select id="leftValues" name="availablePages[]" size="5" multiple>
                                <?php foreach ($availablePages as $page): ?>
                                    <?php if (!in_array($page['id'], array_column($chosenPages, 'id'))): ?>
                                        <option value="<?php echo $page['id']; ?>"><?php echo $page['friendlyName']; ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <button type="button" onclick="moveToAvailable()"> &lt;</button>
                            <button type="button" onclick="moveToChosen()"> &gt; </button>
                        </td>
                        <td>
                            <select id="rightValues" name="chosenPages[]" size="5" multiple>
                                <?php foreach ($chosenPages as $page): ?>
                                    <option value="<?php echo $page['id']; ?>"><?php echo $page['friendlyName']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <button type="submit">Save Permissions</button>
            </form>
            <?php
            if (isset($result)) {
                echo $result;
            }
            ?>
        </main>
    </section>


</body>

<script src="/Plagiarism_Checker/public/assets/js/managePermissions.js"></script>

</html>