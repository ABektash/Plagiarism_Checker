<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel='stylesheet' href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css'>
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/forumsManagement.css">

    <title>Plagiarism Detection</title>
</head>

<body>
    <?php include 'inc/sidebar.php'; ?>

    <section id="content">
        <?php include 'inc/navbar.php'; ?>

        <main>
            <div class="head-title">
                <h1>Forums</h1>
            </div>

            <div class="table-section">
                <h2 class="forums-h2">Summary</h2>
                <table>
                    <thead>
                        <tr>
                            <th class="forum-id-th">Forum ID</th>
                            <th class="forum-title-th">Forum Title</th>
                            <th class="forum-content-th">Forum content</th>
                            <th class="forum-actions-th">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>thesis</td>
                            <td>A thesis is an interpretation of a question or subject,
                                not the subject itself. The subject, or topic,
                                of an essay might be World War II or Moby Dick;
                                a thesis must then offer a way to understand the war or the novel.
                                makes a claim that others might dispute.</td>
                            <td class="forum-actions-td"><a class="a-link" href="#"> <i class='bx bx-trash'></i> </a></td>

                        </tr>
                        <tr>
                            <td>2</td>
                            <td>25</td>
                            <td>20</td>
                            <td class="forum-actions-td"><a class="a-link" href="#"><i class='bx bx-trash'></i></a></td>
                        </tr>
                    </tbody>
                </table>

            </div>

        </main>

    </section>

</body>

</html>