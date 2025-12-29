<?php
$id = $_GET['id'] ?? null;
if (!$id) { die('Missing id'); }
include_once '../../../system/cogs/functions.php';$row = fetchData("SELECT * FROM `blog` WHERE `id` = ?", [$id])[0] ?? null;
if (!$row) { die('Record not found'); }
?>
<div class="max-w-3xl mx-auto mt-8 bg-white p-6 rounded-lg shadow-md"><form id="editblogForm" method="POST" action="/update/blog?id=<?= $id ?>" class="space-y-6">
        
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='Blog_id'>Blog_id</label>
                                <input type='int(11)' id='Blog_id' name='Blog_id' required 
                                    value='<?= htmlspecialchars($row["Blog_id"] ?? "") ?>'
                                     placeholder="Enter Blog_id" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='Title'>Title</label>
                                <input type='varchar(80)' id='Title' name='Title' required 
                                    value='<?= htmlspecialchars($row["Title"] ?? "") ?>'
                                     placeholder="Enter Title" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='Slug'>Slug</label>
                                <input type='varchar(100)' id='Slug' name='Slug' required 
                                    value='<?= htmlspecialchars($row["Slug"] ?? "") ?>'
                                     placeholder="Enter Slug" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='Author'>Author</label>
                                <input type='int(11)' id='Author' name='Author' required 
                                    value='<?= htmlspecialchars($row["Author"] ?? "") ?>'
                                     placeholder="Enter Author" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='Post_image'>Post_image</label>
                                <input type='text' id='Post_image' name='Post_image' required 
                                    value='<?= htmlspecialchars($row["Post_image"] ?? "") ?>'
                                     placeholder="Enter Post_image" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='Content'>Content</label>
                                <input type='text' id='Content' name='Content' required 
                                    value='<?= htmlspecialchars($row["Content"] ?? "") ?>'
                                     placeholder="Enter Content" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='short_description'>short_description</label>
                                <input type='varchar(100)' id='short_description' name='short_description' required 
                                    value='<?= htmlspecialchars($row["short_description"] ?? "") ?>'
                                     placeholder="Enter short_description" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='Category'>Category</label>
                                <input type='int(11)' id='Category' name='Category' required 
                                    value='<?= htmlspecialchars($row["Category"] ?? "") ?>'
                                     placeholder="Enter Category" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='Tags'>Tags</label>
                                <input type='text' id='Tags' name='Tags' required 
                                    value='<?= htmlspecialchars($row["Tags"] ?? "") ?>'
                                     placeholder="Enter Tags" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='Read_Time'>Read_Time</label>
                                <input type='varchar(30)' id='Read_Time' name='Read_Time' required 
                                    value='<?= htmlspecialchars($row["Read_Time"] ?? "") ?>'
                                     placeholder="Enter Read_Time" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='Status'>Status</label>
                                <input type='enum(&#039;Published&#039;,&#039;Draft&#039;,&#039;Archived&#039;,&#039;hidden&#039;)' id='Status' name='Status' required 
                                    value='<?= htmlspecialchars($row["Status"] ?? "") ?>'
                                     placeholder="Enter Status" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='Publish_Date'>Publish_Date</label>
                                <input type='timestamp' id='Publish_Date' name='Publish_Date' required 
                                    value='<?= htmlspecialchars($row["Publish_Date"] ?? "") ?>'
                                     placeholder="Enter Publish_Date" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='Last_Updated'>Last_Updated</label>
                                <input type='datetime' id='Last_Updated' name='Last_Updated' required 
                                    value='<?= htmlspecialchars($row["Last_Updated"] ?? "") ?>'
                                     placeholder="Enter Last_Updated" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='Meta_Title'>Meta_Title</label>
                                <input type='varchar(50)' id='Meta_Title' name='Meta_Title' required 
                                    value='<?= htmlspecialchars($row["Meta_Title"] ?? "") ?>'
                                     placeholder="Enter Meta_Title" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='Meta_Description'>Meta_Description</label>
                                <input type='varchar(50)' id='Meta_Description' name='Meta_Description' required 
                                    value='<?= htmlspecialchars($row["Meta_Description"] ?? "") ?>'
                                     placeholder="Enter Meta_Description" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='Comments_Enabled'>Comments_Enabled</label>
                                <input type='enum(&#039;1&#039;,&#039;0&#039;)' id='Comments_Enabled' name='Comments_Enabled' required 
                                    value='<?= htmlspecialchars($row["Comments_Enabled"] ?? "") ?>'
                                     placeholder="Enter Comments_Enabled" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='allow_category_number'>allow_category_number</label>
                                <input type='int(11)' id='allow_category_number' name='allow_category_number' required 
                                    value='<?= htmlspecialchars($row["allow_category_number"] ?? "") ?>'
                                     placeholder="Enter allow_category_number" 
                                    
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
                Update blog
            </button>
        </div>
    </form>
    </div>