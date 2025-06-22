---
layout: default
title: Order Info
nav_order: 7
has_children: true
permalink: /order-info/
---

# Order Info

Information retrieval endpoints for accessing comprehensive details about orders and tickets after booking completion.

These endpoints provide detailed information about existing orders and tickets, including passenger data, route information, payment status, cancellation policies, and download links for ticket documents.



## Order Information Workflow

### Post-Booking Information Access
1. **Order Completion** → Customer completes booking through `new_order` → `payment`
2. **Order Tracking** → Use `get_order` for comprehensive order status and details
3. **Ticket Management** → Use `get_ticket` for individual ticket information and PDF access
4. **Customer Service** → Access passenger details, payment history, and cancellation options



## Available Endpoints

### get_order
Retrieve comprehensive information about an entire order including all routes, passengers, payment methods, and order history. This endpoint provides a complete overview of the booking status and is essential for order management and customer service.

### get_ticket
Retrieve detailed information about specific tickets or all tickets within an order. This endpoint provides comprehensive ticket data needed for passenger management, boarding verification, and customer service.
