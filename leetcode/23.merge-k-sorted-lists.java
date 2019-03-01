/*
 * @lc app=leetcode id=23 lang=java
 *
 * [23] Merge k Sorted Lists
 *
 * https://leetcode.com/problems/merge-k-sorted-lists/description/
 *
 * algorithms
 * Hard (32.93%)
 * Total Accepted:    341.4K
 * Total Submissions: 1M
 * Testcase Example:  '[[1,4,5],[1,3,4],[2,6]]'
 *
 * Merge k sorted linked lists and return it as one sorted list. Analyze and
 * describe its complexity.
 * 
 * Example:
 * 
 * 
 * Input:
 * [
 * 1->4->5,
 * 1->3->4,
 * 2->6
 * ]
 * Output: 1->1->2->3->4->4->5->6
 * 
 * 
 */
/**
 * Definition for singly-linked list.
 * public class ListNode {
 *     int val;
 *     ListNode next;
 *     ListNode(int x) { val = x; }
 * }
 */
class Solution {
    public ListNode mergeKLists(ListNode[] lists) {
        ListNode head = null;
        ListNode temp = head;
        while (true) {
            int min = -1;
            for (int i = 0; i < lists.length; i++) {
                ListNode listNode = lists[i];
                if (listNode != null) {
                    if (min == -1) {
                        min = i;
                    } else {
                        if (lists[min].val > listNode.val) {
                            min = i;
                        }
                    }
                }
            }
            if (min < 0) {
                break;
            } else {
                ListNode listNode = new ListNode(lists[min].val);
                lists[min] = lists[min].next;
                if (head == null) {
                    head = listNode;
                    temp = head;
                } else {
                    temp.next = listNode;
                    temp = temp.next;
                }
            }
        }
        return head;
    }
}

