<?php
$id = $_GET['id'] ?? null;
if (!$id) { die('Missing id'); }
include_once '../../../system/cogs/functions.php';$row = fetchData("SELECT * FROM `blog_page` WHERE `id` = ?", [$id])[0] ?? null;
if (!$row) { die('Record not found'); }
?>
<div class="max-w-3xl mx-auto mt-8 bg-white p-6 rounded-lg shadow-md"><form id="editblog_pageForm" method="POST" action="/update/blog_page?id=<?= $id ?>" class="space-y-6">
        
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='blog_page_id'>blog_page_id</label>
                                <input type='int(11)' id='blog_page_id' name='blog_page_id' required 
                                    value='<?= htmlspecialchars($row["blog_page_id"] ?? "") ?>'
                                     placeholder="Enter blog_page_id" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='view_search'>view_search</label>
                                <input type='int(11)' id='view_search' name='view_search' required 
                                    value='<?= htmlspecialchars($row["view_search"] ?? "") ?>'
                                     placeholder="Enter view_search" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='view_categories'>view_categories</label>
                                <input type='int(11)' id='view_categories' name='view_categories' required 
                                    value='<?= htmlspecialchars($row["view_categories"] ?? "") ?>'
                                     placeholder="Enter view_categories" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='view_recent_blog'>view_recent_blog</label>
                                <input type='int(11)' id='view_recent_blog' name='view_recent_blog' required 
                                    value='<?= htmlspecialchars($row["view_recent_blog"] ?? "") ?>'
                                     placeholder="Enter view_recent_blog" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='recent_blog_number'>recent_blog_number</label>
                                <input type='int(11)' id='recent_blog_number' name='recent_blog_number' required 
                                    value='<?= htmlspecialchars($row["recent_blog_number"] ?? "") ?>'
                                     placeholder="Enter recent_blog_number" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='view_blog_tags'>view_blog_tags</label>
                                <input type='int(11)' id='view_blog_tags' name='view_blog_tags' required 
                                    value='<?= htmlspecialchars($row["view_blog_tags"] ?? "") ?>'
                                     placeholder="Enter view_blog_tags" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='custom_html'>custom_html</label>
                                <input type='text' id='custom_html' name='custom_html' required 
                                    value='<?= htmlspecialchars($row["custom_html"] ?? "") ?>'
                                     placeholder="Enter custom_html" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='img_after_recent_post'>img_after_recent_post</label>
                                <input type='text' id='img_after_recent_post' name='img_after_recent_post' required 
                                    value='<?= htmlspecialchars($row["img_after_recent_post"] ?? "") ?>'
                                     placeholder="Enter img_after_recent_post" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='img_after_tag'>img_after_tag</label>
                                <input type='text' id='img_after_tag' name='img_after_tag' required 
                                    value='<?= htmlspecialchars($row["img_after_tag"] ?? "") ?>'
                                     placeholder="Enter img_after_tag" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='Plain_Text_title'>Plain_Text_title</label>
                                <input type='varchar(20)' id='Plain_Text_title' name='Plain_Text_title' required 
                                    value='<?= htmlspecialchars($row["Plain_Text_title"] ?? "") ?>'
                                     placeholder="Enter Plain_Text_title" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='Plain_Text_description'>Plain_Text_description</label>
                                <input type='varchar(100)' id='Plain_Text_description' name='Plain_Text_description' required 
                                    value='<?= htmlspecialchars($row["Plain_Text_description"] ?? "") ?>'
                                     placeholder="Enter Plain_Text_description" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='blog_style'>blog_style</label>
                                <input type='enum(&#039;1&#039;,&#039;2&#039;,&#039;3&#039;,&#039;4&#039;)' id='blog_style' name='blog_style' required 
                                    value='<?= htmlspecialchars($row["blog_style"] ?? "") ?>'
                                     placeholder="Enter blog_style" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='pagination_style'>pagination_style</label>
                                <input type='enum(&#039;1&#039;,&#039;2&#039;,&#039;3&#039;,&#039;5&#039;,&#039;6&#039;,&#039;7&#039;)' id='pagination_style' name='pagination_style' required 
                                    value='<?= htmlspecialchars($row["pagination_style"] ?? "") ?>'
                                     placeholder="Enter pagination_style" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='show_author'>show_author</label>
                                <input type='int(11)' id='show_author' name='show_author' required 
                                    value='<?= htmlspecialchars($row["show_author"] ?? "") ?>'
                                     placeholder="Enter show_author" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='show_author_img'>show_author_img</label>
                                <input type='int(11)' id='show_author_img' name='show_author_img' required 
                                    value='<?= htmlspecialchars($row["show_author_img"] ?? "") ?>'
                                     placeholder="Enter show_author_img" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='show_single_category'>show_single_category</label>
                                <input type='int(11)' id='show_single_category' name='show_single_category' required 
                                    value='<?= htmlspecialchars($row["show_single_category"] ?? "") ?>'
                                     placeholder="Enter show_single_category" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='title_limit'>title_limit</label>
                                <input type='varchar(50)' id='title_limit' name='title_limit' required 
                                    value='<?= htmlspecialchars($row["title_limit"] ?? "") ?>'
                                     placeholder="Enter title_limit" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='description_limit'>description_limit</label>
                                <input type='tinyint(255)' id='description_limit' name='description_limit' required 
                                    value='<?= htmlspecialchars($row["description_limit"] ?? "") ?>'
                                     placeholder="Enter description_limit" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='cta_label'>cta_label</label>
                                <input type='varchar(20)' id='cta_label' name='cta_label' required 
                                    value='<?= htmlspecialchars($row["cta_label"] ?? "") ?>'
                                     placeholder="Enter cta_label" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='go-to-page'>go-to-page</label>
                                <input type='varchar(100)' id='go-to-page' name='go-to-page' required 
                                    value='<?= htmlspecialchars($row["go-to-page"] ?? "") ?>'
                                     placeholder="Enter go-to-page" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='show-date'>show-date</label>
                                <input type='int(11)' id='show-date' name='show-date' required 
                                    value='<?= htmlspecialchars($row["show-date"] ?? "") ?>'
                                     placeholder="Enter show-date" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='show-pagination'>show-pagination</label>
                                <input type='int(11)' id='show-pagination' name='show-pagination' required 
                                    value='<?= htmlspecialchars($row["show-pagination"] ?? "") ?>'
                                     placeholder="Enter show-pagination" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='show-comment'>show-comment</label>
                                <input type='int(11)' id='show-comment' name='show-comment' required 
                                    value='<?= htmlspecialchars($row["show-comment"] ?? "") ?>'
                                     placeholder="Enter show-comment" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='showCategoryIcon'>showCategoryIcon</label>
                                <input type='varchar(30)' id='showCategoryIcon' name='showCategoryIcon' required 
                                    value='<?= htmlspecialchars($row["showCategoryIcon"] ?? "") ?>'
                                     placeholder="Enter showCategoryIcon" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='number_of_post'>number_of_post</label>
                                <input type='int(11)' id='number_of_post' name='number_of_post' required 
                                    value='<?= htmlspecialchars($row["number_of_post"] ?? "") ?>'
                                     placeholder="Enter number_of_post" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='created_at'>created_at</label>
                                <input type='timestamp' id='created_at' name='created_at' required 
                                    value='<?= htmlspecialchars($row["created_at"] ?? "") ?>'
                                     placeholder="Enter created_at" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                <div>
            <button type="submit" class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700 transition">
                Update blog_page
            </button>
        </div>
    </form>
    </div>