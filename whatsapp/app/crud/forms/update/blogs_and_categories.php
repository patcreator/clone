<?php
$id = $_GET['id'] ?? null;
if (!$id) { die('Missing id'); }
include_once '../../../system/cogs/functions.php';$row = fetchData("SELECT * FROM `blogs_and_categories` WHERE `id` = ?", [$id])[0] ?? null;
if (!$row) { die('Record not found'); }
?>
<div class="max-w-3xl mx-auto mt-8 bg-white p-6 rounded-lg shadow-md"><form id="editblogs_and_categoriesForm" method="POST" action="/update/blogs_and_categories?id=<?= $id ?>" class="space-y-6">
        
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='BAC_ID'>BAC_ID</label>
                                <input type='int(11)' id='BAC_ID' name='BAC_ID' required 
                                    value='<?= htmlspecialchars($row["BAC_ID"] ?? "") ?>'
                                     placeholder="Enter BAC_ID" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='blog'>blog</label>
                                <input type='int(11)' id='blog' name='blog' required 
                                    value='<?= htmlspecialchars($row["blog"] ?? "") ?>'
                                     placeholder="Enter blog" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='category'>category</label>
                                <input type='int(11)' id='category' name='category' required 
                                    value='<?= htmlspecialchars($row["category"] ?? "") ?>'
                                     placeholder="Enter category" 
                                    
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
                Update blogs_and_categories
            </button>
        </div>
    </form>
    </div>