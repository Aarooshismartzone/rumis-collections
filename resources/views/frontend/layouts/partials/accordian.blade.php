<div class="accordion-background" style="background-image: url('{{ asset('images/accordian/1.jpg') }}');">
    <div class="accordion-overlay">
        <!-- Content inside overlay -->
        <div class="container">
            <h5>{{$customer ? 'Dear '.$customer->fname.',' : ''}}</h5>
            <h1 class="main-title">Welcome To <span>Rumi's</span></h1>
            <h4>Where Life And Style Come Together</h4>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        let basePath = "{{ asset('images/accordian') }}/";
        let currentIndex = 1; // Start with 1.jpg
        const interval = 5000; // Change every 5 seconds

        function updateBackground() {
            $(".accordion-background").css(
                "background-image",
                `url('${basePath}${currentIndex}.jpg')`
            );
            currentIndex++; // Move to the next image
            if (!imageExists(basePath + currentIndex + ".jpg")) {
                currentIndex = 1; // Reset to 1.jpg if the image doesn't exist
            }
        }

        function imageExists(url) {
            let http = new XMLHttpRequest();
            http.open("HEAD", url, false);
            http.send();
            return http.status !== 404;
        }

        // Set interval to change the background
        setInterval(updateBackground, interval);
    });
</script>
