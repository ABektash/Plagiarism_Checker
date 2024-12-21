<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submission Report</title>

    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/viewReport.css">
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/header.css">
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="/Plagiarism_Checker/public/assets/js/viewReport.js" defer></script>
</head>



<body>
    <?php include 'inc/header.php'; ?>

    <div class="container">

        <section class="section">
            <h2>Report Details</h2>
            <div class="report-data">
                <p><span>Assignment Title:</span> <?php echo htmlspecialchars($assignmentTitle ?? 'N/A', ENT_QUOTES); ?></p>
                <p><span>Submitted By:</span> <?php echo htmlspecialchars($userName ?? 'N/A', ENT_QUOTES); ?></p>
                <p><span>Submission Date:</span> <?php echo htmlspecialchars($submissionTime ?? 'N/A', ENT_QUOTES); ?></p>
                <p><span>Due Date:</span> <?php echo htmlspecialchars($assignmentDue ?? 'N/A', ENT_QUOTES); ?></p>
                <p><span>Similarity Percentage:</span> <?php echo htmlspecialchars($similarity ?? 'N/A', ENT_QUOTES); ?>%</p>
                <p><span>Grade:</span> <?php echo htmlspecialchars($grade ?? 'N/A', ENT_QUOTES); ?>/100</p>
            </div>
        </section>

        <section class="section">
            <h2>Similarity Breakdown</h2>
            <div class="piechart" style="background: conic-gradient(#4caf50 <?php echo htmlspecialchars($similarity ?? 0, ENT_QUOTES); ?>%, #ccc 0%);"></div>
            <div class="legend-container">
                <div class="legend">
                    <div class="green-box"></div>
                    <span><?php echo htmlspecialchars($similarity ?? 'N/A', ENT_QUOTES); ?>% Similarity</span>
                </div>
                <div class="legend">
                    <div class="grey-box"></div>
                    <span><?php echo 100 - (float)($similarity ?? 0); ?>% Original</span>
                </div>
            </div>
        </section>

        <?php if (!empty($feedback)): ?>
            <section class="section full-width">
                <h2>Instructor Feedback</h2>
                <p><?php echo nl2br(htmlspecialchars($feedback, ENT_QUOTES)); ?></p>

                <?php if ($_SESSION['user']['UserType_id'] == 3): ?>
                    <div style="margin-top: 20px; text-align: right;">
                        <button id="contact-instructor-btn">Contact Instructor</button>
                    </div>

                <?php endif; ?>

            </section>
        <?php else: ?>
            <section class="section full-width">
                <h2>Instructor Feedback</h2>
                <p>No feedback available.</p>
            </section>
        <?php endif; ?>


        <section class="section full-width">
            <h2>Submission Content</h2>
            <pre style="white-space: pre-wrap; word-wrap: break-word;">
            <?php echo htmlspecialchars(preg_replace('/^\{"text":"|"\}$/', '', trim($submissionContent ?? 'No submission content available.')), ENT_QUOTES); ?>
            </pre>
        </section>

        <?php
        $reportDecoded = json_decode($report, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            $originalityScore = $reportDecoded['originalityai']['plagia_score'] ?? 'N/A';
            $analysisItems = $reportDecoded['originalityai']['items'] ?? [];
        } else {
            $originalityScore = 'N/A';
            $analysisItems = [];
            error_log("JSON Decode Error: " . json_last_error_msg());
        }
        ?>

        <section class="section full-width">
            <h2>Report Analysis</h2>

            <?php if (!empty($reportDecoded['originalityai']['items'])): ?>
                <div class="analysis-container">
                    <?php foreach ($reportDecoded['originalityai']['items'] as $index => $item): ?>
                        <div class="text-segment">
                            <h3>Text Segment</h3>
                            <p><?php echo htmlspecialchars($item['text'], ENT_QUOTES); ?></p>
                            <?php if (!empty($item['candidates'])): ?>
                                <h4>Potential Matches</h4>
                                <div class="matches">
                                    <?php foreach ($item['candidates'] as $candidate): ?>
                                        <div class="candidate">
                                            <p><strong>URL:</strong>
                                                <a href="<?php echo htmlspecialchars($candidate['url'], ENT_QUOTES); ?>" target="_blank">
                                                    Visit Source
                                                </a>
                                            </p>
                                            <p><strong>Plagiarism Score:</strong> <?php echo htmlspecialchars($candidate['plagia_score'], ENT_QUOTES); ?></p>
                                            <p><strong>Prediction:</strong> <?php echo htmlspecialchars($candidate['prediction'], ENT_QUOTES); ?></p>
                                            <p><strong>Plagiarized Text:</strong> <?php echo htmlspecialchars($candidate['plagiarized_text'], ENT_QUOTES); ?></p>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>No Analysis.</p>
            <?php endif; ?>

        </section>



        <?php if ($_SESSION['user']['UserType_id'] == 1 || $_SESSION['user']['UserType_id'] == 2): ?>

            <section class="section full-width">
                <h2>Instructor Comments & Grade</h2>
                <div class="textarea-container">
                    <div style="flex: 1; min-width: 200px;">
                        <label for="instructor-grade" style="display: block; font-weight: bold; margin-bottom: 10px;">Grade (Out of 100):</label>
                        <input type="number" id="instructor-grade" name="instructor-grade" placeholder="Enter Grade (Max: 100)" min="0" max="100" required>
                    </div>
                    <div style="flex: 2; min-width: 300px;">
                        <label for="instructor-comment" style="display: block; font-weight: bold; margin-bottom: 10px;">Comments:</label>
                        <textarea id="instructor-comment" name="instructor-comment" rows="5" placeholder="Enter your comments here..." required></textarea>
                    </div>
                </div>
                <div style="margin-top: 20px; text-align: right;">
                    <button id="instructor-Finalize-btn">Finalize Report</button>
                </div>
            </section>

        <?php endif; ?>


    </div>

    <?php include 'inc/footer.php'; ?>

</body>

</html>