<script src="js/jquery.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/table.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-multiselect@0.9.13/dist/js/bootstrap-multiselect.js"></script>
    <script>
    $(document).ready(function() {
        $('#minimal-multiselect').multiselect();
    });
    </script>
    <script>
    function showContent(contentId) {
        // Hide all content divs
        var contents = document.querySelectorAll('.content');
        contents.forEach(function(content) {
            content.style.display = 'none';
        });

        // Remove active class from all list items
        var tabs = document.querySelectorAll('ul li');
        tabs.forEach(function(tab) {
            tab.classList.remove('active');
        });

        // Show the selected content div
        document.getElementById(contentId).style.display = 'block';

        // Add active class to the clicked button's parent li
        event.target.closest('li').classList.add('active');
    }

    // Show the first content by default
    document.getElementById('content1').style.display = 'block';

    $(document).ready(function() {
        $('#multiple-checkboxes').multiselect({
            includeSelectAllOption: true,
        });
    });

    function direct() {
        window.location.href = 'logout';
    }
    </script>