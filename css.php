<!-- Bootstrap CSS v5.2.1 -->
<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
    crossorigin="anonymous" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer" />
<link rel="stylesheet" href="/css/style.css?time=<?= time() ?>">
<style>
    a {
        text-decoration: none;
    }

    .table-container {
        max-height: 620px;
        min-width: 1500px;
        overflow-y: auto;
        overflow-x: hidden;
    }

    .scrollable-cell {
        max-height: 200px;
        overflow-y: auto;
        padding: 5px;
    }

    table td:nth-child(1),
    table th:nth-child(1) {
        min-width: 80px;
        max-width: 200px;
        word-break: break-word;
        /* 自動換行 */
    }

    table td:nth-child(2),
    table th:nth-child(2) {
        min-width: 80px;
        max-width: 200px;
        word-break: break-word;
    }

    table td:nth-child(3),
    table th:nth-child(3) {
        min-width: 80px;
        max-width: 200px;
        word-break: break-word;
    }

    table td:nth-child(4),
    table th:nth-child(4) {
        min-width: 90px;
        max-width: 200px;
        word-break: break-word;
    }

    table td:nth-child(5),
    table th:nth-child(5) {
        min-width: 150px;
        max-width: 200px;
        word-break: break-word;
    }

    table td img {
        width: 150px;
        object-fit: cover;
    }
</style>