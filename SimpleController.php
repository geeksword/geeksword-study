<?php

namespace App\Modules\Leetcode\Http\Controllers;

use App\Modules\Leetcode\ExtClass\ListNode;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use function Symfony\Component\VarDumper\Dumper\esc;

class SimpleController extends Controller
{
    public function index(Request $request, $name)
    {
        if (method_exists($this, $name)) {
            return self::$name();
        } else {
            abort(404);
        }
    }

    /**
     * 1. 两数之和
     * 给定一个整数数组 nums 和一个目标值 target，请你在该数组中找出和为目标值的那 两个 整数，并返回他们的数组下标。
     * 你可以假设每种输入只会对应一个答案。但是，你不能重复利用这个数组中同样的元素。
     * @return array
     */
    protected function twoSum()
    {
        $nums = [2, 7, 11, 15];
        $target = 9;
        for ($i = 0, $max = count($nums); $i<$max; $i++) {
            for ($j = $i+1; $j<$max; $j++) {
                if ($nums[$i] + $nums[$j] == $target) {
                    return [$i, $j];
                }
            }
        }
    }

    /**
     * 7. 整数反转
     * 给出一个 32 位的有符号整数，你需要将这个整数中每位上的数字进行反转。
     */
    protected function reverse()
    {
        $x = 10;
        if ($x == 0) {
            return 0;
        }
        $symbol =  $x / abs($x);
        $reverse = '';
        $x = abs($x);
        while ($x>=10) {
            $res = $x%10;
            $x = (int)$x/10;
            $reverse .= $res;
        }
        $reverse = (int)$reverse . $x;
        if ($reverse > 2147483647 || $reverse < -2147483648) {
            return 0;
        }
        return $symbol * (int)$reverse;
    }

    /**
     * 9. 回文数
     * 判断一个整数是否是回文数。回文数是指正序（从左向右）和倒序（从右向左）读都是一样的整数。
     */
    public function isPalindrome()
    {
        $temp = $x = 121;
        if ($x < 0) {
            return false;
        }
        $reverse = '';
        while ($x>=10) {
            $res = $x%10;
            $x = floor($x/10);
            $reverse .= $res;
        }
        $reverse .= $x;
        return (int)($reverse == $temp);
    }

    /**
     * 13. 罗马数字转整数
     * 罗马数字包含以下七种字符: I， V， X， L，C，D 和 M。
     *
     * I 可以放在 V (5) 和 X (10) 的左边，来表示 4 和 9。
     * X 可以放在 L (50) 和 C (100) 的左边，来表示 40 和 90。
     * C 可以放在 D (500) 和 M (1000) 的左边，来表示 400 和 900。
     */
    public function romanToInt()
    {
        $s = 'MCMXCIV';

        $roman = [
            'I' =>  1,
            'V' =>  5,
            'X' =>  10,
            'L' =>  50,
            'C' =>  100,
            'D' =>  500,
            'M' =>  1000,
        ];

        $result = 0;

        for ($i = 0, $iMax = strlen($s); $i<$iMax; $i++) {
            if ($s[$i] === 'I') {
                if ($s[$i+1] === 'V') {
                    $result += 4;
                    $i++;
                    continue;
                } elseif ($s[$i+1] === 'X') {
                    $result += 9;
                    $i++;
                    continue;
                } else {
                    $result += $roman[$s[$i]];
                }
            } elseif ($s[$i] === 'X') {
                if ($s[$i+1] === 'L') {
                    $result += 40;
                    $i++;
                    continue;
                } elseif ($s[$i+1] === 'C') {
                    $result += 90;
                    $i++;
                    continue;
                } else {
                    $result += $roman[$s[$i]];
                }
            } elseif ($s[$i] === 'C') {
                if ($s[$i+1] === 'D') {
                    $result += 400;
                    $i++;
                    continue;
                } elseif ($s[$i+1] === 'M') {
                    $result += 900;
                    $i++;
                    continue;
                } else {
                    $result += $roman[$s[$i]];
                }
            } else {
                $result += $roman[$s[$i]];
            }
        }

        return $result;
    }

    /**
     * 14. 最长公共前缀
     * 查找字符串数组中的最长公共前缀。 如果不存在公共前缀，返回空字符串 ""。
     */
    public function longestCommonPrefix()
    {
        $strs = ["flower","flow","flight"];
        if (count($strs) == 1) {
            return $strs[0];
        }
        $prefix = '';
        for ($i = 1, $iMax = count($strs); $i < $iMax; $i++) {
            $temp = '';
            if ($i == 1) {
                $first_str = $strs[0];
                $sec_str = $strs[1];
            } else {
                $first_str = $prefix;
                $sec_str = $strs[$i];
            }
            for ($j = 0, $jMax = strlen($first_str); $j < $jMax; $j++) {
                if (isset($sec_str[$j])) {
                    if ($first_str[$j] == $sec_str[$j]) {
                        $temp .= $first_str[$j];
                        continue;
                    } else {
                        break;
                    }
                } else {
                    break;
                }
            }
            $prefix = $temp;
        }
        return $prefix;
    }

    /**
     * 20. 有效的括号
     * 给定一个只包括 '('，')'，'{'，'}'，'['，']' 的字符串，判断字符串是否有效。
     * 有效字符串需满足：
     *      1. 左括号必须用相同类型的右括号闭合。
     *      2. 左括号必须以正确的顺序闭合。
     * 注意空字符串可被认为是有效字符串。
     */
    public function isValid()
    {
        $s = "((";

        $brackets = [
            '(' => ')',
            '{' => '}',
            '[' => ']',
        ];

        if (strlen($s)%2 != 0) {
            return 0;
        }

        $stack = [];

        for ($i = 0, $iMax = strlen($s); $i< $iMax; $i++){
            if (array_key_exists($s[$i], $brackets)){
                $stack[] = $s[$i];
            }else{
                if ($brackets[array_pop($stack)] != $s[$i]){
                    return 0;
                }
                continue;
            }
        }
        
        if (count($stack) > 0){
            return 0;
        }
        
        return 1;
    }

    /**
     * 21. 合并两个有序链表
     * 将两个有序链表合并为一个新的有序链表并返回。新链表是通过拼接给定的两个链表的所有节点组成的。
     * 示例：
     *      输入：1->2->4, 1->3->4
     *      输出：1->1->2->3->4->4
     *
     * class ListNode {
     *     public $val = 0;
     *     public $next = null;
     *     function __construct($val) { $this->val = $val; }
     * }
     */
    public function mergeTwoLists()
    {
        $l1 = new ListNode(1);
        $l12 = new ListNode(2);
        $l13 = new ListNode(3);

        $l12->next = $l13;

        $l1->next = $l12;

        $l2 = new ListNode(1);
        $l22 = new ListNode(3);
        $l23 = new ListNode(4);

        $l22->next = $l23;

        $l2->next = $l22;

        if ($l1 == null){
            return $l2;
        }

        if ($l2 == null){
            return $l1;
        }

        if ($l1->val < $l2->val){
            $result = $l1;
            $l1 = $l1->next;
        }else{
            $result = $l2;
            $l2 = $l2->next;
        }

        $p = $result;

        while ($l1 && $l2){
            if ($l1->val <= $l2->val){
                $p->next = $l1;
                $l1 = $l1->next;
            }else{
                $p->next = $l2;
                $l2 = $l2->next;
            }
            $p = $p->next;
        }

        if ($l1 != null){
            $p->next = $l1;
        }

        if ($l2 != null){
            $p->next = $l2;
        }

        dd($result);
    }

    /**
     * 26. 删除排序数组中的重复项
     *
     * 给定一个排序数组，你需要在原地删除重复出现的元素，使得每个元素只出现一次，返回移除后数组的新长度。
     * 不要使用额外的数组空间，你必须在原地修改输入数组并在使用 O(1) 额外空间的条件下完成。
     *
     * 给定 nums = [0,0,1,1,1,2,2,3,3,4],
     * 函数应该返回新的长度 5, 并且原数组 nums 的前五个元素被修改为 0, 1, 2, 3, 4。
     *
     * @return Integer
     */
    public function removeDuplicates(&$nums = [0,0,1,1,1,2,2,3,3,4])
    {
        if (count($nums) == 0){
            return 0;
        }

        $i = 0; $j = 0;

        while ($j < count($nums)){
            if ($nums[$i] != $nums[$j]){
                $nums[++$i] = $nums[$j];
            }
            $j++;
        }

        return $i + 1;
    }
    
    /**
     * 27. 移除元素
     * 给定一个数组 nums 和一个值 val，你需要原地移除所有数值等于 val 的元素，返回移除后数组的新长度。
     * 不要使用额外的数组空间，你必须在原地修改输入数组并在使用 O(1) 额外空间的条件下完成。
     * 元素的顺序可以改变。你不需要考虑数组中超出新长度后面的元素。
     *
     * 示例 1:
     *      给定 nums = [3,2,2,3], val = 3,
     *      函数应该返回新的长度 2, 并且 nums 中的前两个元素均为 2。
     *      你不需要考虑数组中超出新长度后面的元素。
     * @return Integer
     */
    public function removeElement(&$nums = [3,2,2,3], $val = 3)
    {
        foreach ($nums as $key => $value){
            if ($val == $value){
                unset($nums[$key]);
            }
        }
        return count($nums);
    }

    /**
     * 28. 实现strStr()
     * 给定一个 haystack 字符串和一个 needle 字符串，在 haystack 字符串中找出 needle 字符串出现的第一个位置 (从0开始)。
     * 如果不存在，则返回  -1。
     *
     * 示例 1:
     *      输入: haystack = "hello", needle = "ll"
     *      输出: 2
     */
    public function strStr($haystack = 'mississippi', $needle = 'pi')
    {
        if (strlen($haystack) < strlen($needle)){
            return -1;
        }

        if ($haystack == $needle){
            return 0;
        }

        if ($needle == ''){
            return 0;
        }

        for($i = 0, $iMax = strlen($haystack) - strlen($needle)+1; $i < $iMax; $i++){
            $flag = true;
            if ($haystack[$i] == $needle[0]){
                for($j = 1, $jMax = strlen($needle); $j< $jMax; $j++){
                    if ($needle[$j] == $haystack[$i+$j]){
                        continue;
                    }else{
                        $flag = false;
                        break;
                    }
                }
                if ($flag){
                    return $i;
                }
            }

        }
        return -1;
    }

    /**
     * 35. 搜索插入位置
     * 给定一个排序数组和一个目标值，在数组中找到目标值，并返回其索引。如果目标值不存在于数组中，返回它将会被按顺序插入的位置。
     * 你可以假设数组中无重复元素。
     */
    public function searchInsert($nums = [1,3,5,6], $target = 5)
    {
        if ($index = array_search($target, $nums)){
            return $index;
        }

        foreach ($nums as $key => $val){
            if ($val >= $target){
                return $key;
            }
        }
        return $key+1;
    }
    
    /**
     * 38. 报数
     * 报数序列是一个整数序列，按照其中的整数的顺序进行报数，得到下一个数。其前五项如下：
     *  1.     1
     *  2.     11
     *  3.     21
     *  4.     1211
     *  5.     111221
     * 1 被读作  "one 1"  ("一个一") , 即 11。
     * 11 被读作 "two 1s" ("两个一"）, 即 21。
     * 21 被读作 "one 2",  "one 1" （"一个二" ,  "一个一") , 即 1211。
     */
    public function countAndSay()
    {
        
    }

}
