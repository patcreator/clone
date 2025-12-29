<?php
$id = $_GET['id'] ?? null;
if (!$id) { die('Missing id'); }
include_once '../../../system/cogs/functions.php';$row = fetchData("SELECT * FROM `blog_categories` WHERE `id` = ?", [$id])[0] ?? null;
if (!$row) { die('Record not found'); }
?>
<div class="max-w-3xl mx-auto mt-8 bg-white p-6 rounded-lg shadow-md"><form id="editblog_categoriesForm" method="POST" action="/update/blog_categories?id=<?= $id ?>" class="space-y-6">
        
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='blog_category_id'>blog_category_id</label>
                                <input type='int(11)' id='blog_category_id' name='blog_category_id' required 
                                    value='<?= htmlspecialchars($row["blog_category_id"] ?? "") ?>'
                                     placeholder="Enter blog_category_id" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='blog_category_name'>blog_category_name</label>
                                <input type='varchar(30)' id='blog_category_name' name='blog_category_name' required 
                                    value='<?= htmlspecialchars($row["blog_category_name"] ?? "") ?>'
                                     placeholder="Enter blog_category_name" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='blog_category_status'>blog_category_status</label>
                                <input type='enum(&#039;active&#039;,&#039;notactive&#039;)' id='blog_category_status' name='blog_category_status' required 
                                    value='<?= htmlspecialchars($row["blog_category_status"] ?? "") ?>'
                                     placeholder="Enter blog_category_status" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='blog_category_slug'>blog_category_slug</label>
                                <input type='varchar(15)' id='blog_category_slug' name='blog_category_slug' required 
                                    value='<?= htmlspecialchars($row["blog_category_slug"] ?? "") ?>'
                                     placeholder="Enter blog_category_slug" 
                                    
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
                Update blog_categories
            </button>
        </div>
    </form>
    </div>