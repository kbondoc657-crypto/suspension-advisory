function filter() {

    let dropdown = document.querySelectorAll(".dropdown");

    for(let i = 0; dropdown.length > i; i++) {

        let dropdownSeleted = dropdown[i];

        const select = dropdownSeleted.querySelector(".select");
        const caret = dropdownSeleted.querySelector(".caret");
        const menu = dropdownSeleted.querySelector(".dropdown-list");
        const options = dropdownSeleted.querySelectorAll(".dropdown-list li");
        const selected = dropdownSeleted.querySelector(".selected");

            select.addEventListener("click", () => {
                select.classList.toggle("select-clicked");
                caret.classList.toggle("caret-rotate");
                menu.classList.toggle("menu-open")

              
            })

         if(selected) {
            options.forEach(option => {
                option.addEventListener("click", () => {
                    selected.innerText = option.innerText;
                    select.classList.remove("select-clicked");
                    caret.classList.remove("caret-rotate");
                    menu.classList.remove("menu-open");
                    options.forEach(option => {
                        option.classList.remove("active")
                    })
                    option.classList.add("active")
                })
            })

        }

    }
 
} 
filter();













    document.addEventListener('DOMContentLoaded', function() {
        let currentPage = 1;
        let selectedProvince = '';
        let selectedCity = '';
        
        const announcementsContainer = document.querySelector('.annoucements-container');
        const announcementsNumber = document.querySelector('.annoucements-number');
        const paginationContainer = document.querySelector('.pagination-container');
        
        // Province filter
        const provinceItems = document.querySelectorAll('.dropdown-list li.province');
        provinceItems.forEach(function(item) {
            item.addEventListener('click', function() {
                selectedProvince = this.getAttribute('data-value');
                selectedCity = ''; // Reset city when province changes
                currentPage = 1;
                loadPosts();
            });
        });
        
        // City filter
        const cityItems = document.querySelectorAll('.dropdown-list li.city');
        cityItems.forEach(function(item) {
            item.addEventListener('click', function() {
                selectedCity = this.getAttribute('data-value');
                currentPage = 1;
                loadPosts();
            });
        });
        
        // Pagination click handler (delegated)
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('page-number')) {
                e.preventDefault();
                currentPage = parseInt(e.target.getAttribute('data-page'));
                loadPosts();
                
                // Scroll to top of announcements
                announcementsContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
        
        // Load posts function
        function loadPosts() {
            // Show loading state
            const wrapper = document.querySelector('.annoucements-wrapper');
            if (wrapper) {
                announcementsContainer.style.opacity = '0.5';
                announcementsContainer.style.pointerEvents = 'none';
            }
            
            const formData = new FormData();
            formData.append('action', 'filter_advisory_posts');
            formData.append('province', selectedProvince);
            formData.append('city', selectedCity);
            formData.append('paged', currentPage);
            formData.append('posts_per_page', 5);
            
            fetch("<?php echo admin_url('admin-ajax.php'); ?>", {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update announcements count
                    const countText = data.data.total_posts === 1 ? '1 Announcement Found' : data.data.total_posts + ' Announcements Found';
                    announcementsNumber.textContent = countText;
                    
                    // Get existing container content
                    const existingNumber = announcementsContainer.querySelector('.annoucements-number');
                    
                    // Clear container but keep the counter
                    announcementsContainer.innerHTML = '';
                    announcementsContainer.appendChild(existingNumber);
                    
                    // Add new posts
                    announcementsContainer.insertAdjacentHTML('beforeend', data.data.html);
                    
                    // Update pagination
                    updatePagination(data.data.current_page, data.data.max_pages);
                    
                    // Restore opacity
                    announcementsContainer.style.opacity = '1';
                    announcementsContainer.style.pointerEvents = 'auto';
                }
            })
            .catch(error => {
                console.error('Error loading posts:', error);
                announcementsContainer.style.opacity = '1';
                announcementsContainer.style.pointerEvents = 'auto';
            });
        }
        
        // Update pagination
        function updatePagination(current, maxPages) {
            if (!paginationContainer) return;
            
            if (maxPages <= 1) {
                paginationContainer.innerHTML = '';
                return;
            }
            
            let html = '<div class="pagination">';
            
            // Previous button
            if (current > 1) {
                html += '<a href="#" class="page-number prev" data-page="' + (current - 1) + '">← Previous</a>';
            }
            
            // Page numbers
            for (let i = 1; i <= maxPages; i++) {
                if (i === current) {
                    html += '<span class="page-number active">' + i + '</span>';
                } else {
                    html += '<a href="#" class="page-number" data-page="' + i + '">' + i + '</a>';
                }
            }
            
            // Next button
            if (current < maxPages) {
                html += '<a href="#" class="page-number next" data-page="' + (current + 1) + '">Next →</a>';
            }
            
            html += '</div>';
            paginationContainer.innerHTML = html;
        }
    });