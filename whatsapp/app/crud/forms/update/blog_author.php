<?php
$id = $_GET['id'] ?? null;
if (!$id) { die('Missing id'); }
include_once '../../../system/cogs/functions.php';$row = fetchData("SELECT * FROM `blog_author` WHERE `id` = ?", [$id])[0] ?? null;
if (!$row) { die('Record not found'); }
?>
<div class="max-w-3xl mx-auto mt-8 bg-white p-6 rounded-lg shadow-md"><form id="editblog_authorForm" method="POST" action="/update/blog_author?id=<?= $id ?>" class="space-y-6">
        
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='blog_author_id'>blog_author_id</label>
                                <input type='int(11)' id='blog_author_id' name='blog_author_id' required 
                                    value='<?= htmlspecialchars($row["blog_author_id"] ?? "") ?>'
                                     placeholder="Enter blog_author_id" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='blog_author_name'>blog_author_name</label>
                                <input type='varchar(50)' id='blog_author_name' name='blog_author_name' required 
                                    value='<?= htmlspecialchars($row["blog_author_name"] ?? "") ?>'
                                     placeholder="Enter blog_author_name" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='blog_author_img'>blog_author_img</label>
                                <input type='text' id='blog_author_img' name='blog_author_img' required 
                                    value='<?= htmlspecialchars($row["blog_author_img"] ?? "") ?>'
                                     placeholder="Enter blog_author_img" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='blog_author_bio'>blog_author_bio</label>
                                <input type='varchar(100)' id='blog_author_bio' name='blog_author_bio' required 
                                    value='<?= htmlspecialchars($row["blog_author_bio"] ?? "") ?>'
                                     placeholder="Enter blog_author_bio" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='blog_author_email'>blog_author_email</label>
                                <input type='varchar(60)' id='blog_author_email' name='blog_author_email' required 
                                    value='<?= htmlspecialchars($row["blog_author_email"] ?? "") ?>'
                                     placeholder="Enter blog_author_email" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='blog_author_password'>blog_author_password</label>
                                <input type='text' id='blog_author_password' name='blog_author_password' required 
                                    value='<?= htmlspecialchars($row["blog_author_password"] ?? "") ?>'
                                     placeholder="Enter blog_author_password" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='blog_author_phone'>blog_author_phone</label>
                                <input type='varchar(18)' id='blog_author_phone' name='blog_author_phone' required 
                                    value='<?= htmlspecialchars($row["blog_author_phone"] ?? "") ?>'
                                     placeholder="Enter blog_author_phone" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='blog_author_status'>blog_author_status</label>
                                <input type='enum(&#039;active&#039;,&#039;not-active&#039;)' id='blog_author_status' name='blog_author_status' required 
                                    value='<?= htmlspecialchars($row["blog_author_status"] ?? "") ?>'
                                     placeholder="Enter blog_author_status" 
                                    
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
                Update blog_author
            </button>
        </div>
    </form>
    </div>