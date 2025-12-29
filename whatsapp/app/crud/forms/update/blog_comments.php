<?php
$id = $_GET['id'] ?? null;
if (!$id) { die('Missing id'); }
include_once '../../../system/cogs/functions.php';$row = fetchData("SELECT * FROM `blog_comments` WHERE `id` = ?", [$id])[0] ?? null;
if (!$row) { die('Record not found'); }
?>
<div class="max-w-3xl mx-auto mt-8 bg-white p-6 rounded-lg shadow-md"><form id="editblog_commentsForm" method="POST" action="/update/blog_comments?id=<?= $id ?>" class="space-y-6">
        
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='blog_comment_id'>blog_comment_id</label>
                                <input type='int(11)' id='blog_comment_id' name='blog_comment_id' required 
                                    value='<?= htmlspecialchars($row["blog_comment_id"] ?? "") ?>'
                                     placeholder="Enter blog_comment_id" 
                                    
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
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='firstname'>firstname</label>
                                <input type='varchar(50)' id='firstname' name='firstname' required 
                                    value='<?= htmlspecialchars($row["firstname"] ?? "") ?>'
                                     placeholder="Enter firstname" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='lastname'>lastname</label>
                                <input type='varchar(50)' id='lastname' name='lastname' required 
                                    value='<?= htmlspecialchars($row["lastname"] ?? "") ?>'
                                     placeholder="Enter lastname" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='Email'>Email</label>
                                <input type='varchar(100)' id='Email' name='Email' required 
                                    value='<?= htmlspecialchars($row["Email"] ?? "") ?>'
                                     placeholder="Enter Email" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='message'>message</label>
                                <input type='text' id='message' name='message' required 
                                    value='<?= htmlspecialchars($row["message"] ?? "") ?>'
                                     placeholder="Enter message" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='createdDate'>createdDate</label>
                                <input type='timestamp' id='createdDate' name='createdDate' required 
                                    value='<?= htmlspecialchars($row["createdDate"] ?? "") ?>'
                                     placeholder="Enter createdDate" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='Status'>Status</label>
                                <input type='enum(&#039;active&#039;,&#039;not-active&#039;)' id='Status' name='Status' required 
                                    value='<?= htmlspecialchars($row["Status"] ?? "") ?>'
                                     placeholder="Enter Status" 
                                    
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
                Update blog_comments
            </button>
        </div>
    </form>
    </div>