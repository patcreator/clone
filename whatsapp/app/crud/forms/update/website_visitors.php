<?php
$id = $_GET['id'] ?? null;
if (!$id) { die('Missing id'); }
include_once '../../../system/cogs/functions.php';$row = fetchData("SELECT * FROM `website_visitors` WHERE `id` = ?", [$id])[0] ?? null;
if (!$row) { die('Record not found'); }
?>
<div class="max-w-3xl mx-auto mt-8 bg-white p-6 rounded-lg shadow-md"><form id="editwebsite_visitorsForm" method="POST" action="/update/website_visitors?id=<?= $id ?>" class="space-y-6">
        
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='id'>id</label>
                                <input type='int(11)' id='id' name='id' required 
                                    value='<?= htmlspecialchars($row["id"] ?? "") ?>'
                                     placeholder="Enter id" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='ip_address'>ip_address</label>
                                <input type='varchar(45)' id='ip_address' name='ip_address' required 
                                    value='<?= htmlspecialchars($row["ip_address"] ?? "") ?>'
                                     placeholder="Enter ip_address" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='user_agent'>user_agent</label>
                                <input type='text' id='user_agent' name='user_agent' required 
                                    value='<?= htmlspecialchars($row["user_agent"] ?? "") ?>'
                                     placeholder="Enter user_agent" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='referrer_url'>referrer_url</label>
                                <input type='text' id='referrer_url' name='referrer_url' required 
                                    value='<?= htmlspecialchars($row["referrer_url"] ?? "") ?>'
                                     placeholder="Enter referrer_url" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='landing_page'>landing_page</label>
                                <input type='varchar(255)' id='landing_page' name='landing_page' required 
                                    value='<?= htmlspecialchars($row["landing_page"] ?? "") ?>'
                                     placeholder="Enter landing_page" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='visit_time'>visit_time</label>
                                <input type='datetime' id='visit_time' name='visit_time' required 
                                    value='<?= htmlspecialchars($row["visit_time"] ?? "") ?>'
                                     placeholder="Enter visit_time" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='session_id'>session_id</label>
                                <input type='varchar(100)' id='session_id' name='session_id' required 
                                    value='<?= htmlspecialchars($row["session_id"] ?? "") ?>'
                                     placeholder="Enter session_id" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='country'>country</label>
                                <input type='varchar(100)' id='country' name='country' required 
                                    value='<?= htmlspecialchars($row["country"] ?? "") ?>'
                                     placeholder="Enter country" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='browser'>browser</label>
                                <input type='varchar(50)' id='browser' name='browser' required 
                                    value='<?= htmlspecialchars($row["browser"] ?? "") ?>'
                                     placeholder="Enter browser" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='device_type'>device_type</label>
                                <input type='varchar(50)' id='device_type' name='device_type' required 
                                    value='<?= htmlspecialchars($row["device_type"] ?? "") ?>'
                                     placeholder="Enter device_type" 
                                    
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
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='updated_at'>updated_at</label>
                                <input type='timestamp' id='updated_at' name='updated_at' required 
                                    value='<?= htmlspecialchars($row["updated_at"] ?? "") ?>'
                                     placeholder="Enter updated_at" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                            <div>
                                <label class='block text-sm font-medium text-gray-700 mb-1' for='status'>status</label>
                                <input type='enum(&#039;active&#039;,&#039;not-active&#039;)' id='status' name='status' required 
                                    value='<?= htmlspecialchars($row["status"] ?? "") ?>'
                                     placeholder="Enter status" 
                                    
                                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-red-200 focus:border-red-400' />
                            </div>
                    
                <div>
            <button type="submit" class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700 transition">
                Update website_visitors
            </button>
        </div>
    </form>
    </div>