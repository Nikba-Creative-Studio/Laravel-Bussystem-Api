---
layout: default
title: print_ticket
description: Generate and download PDF e-ticket forms for confirmed bookings
nav_order: 1
parent: Print Ticket
---

# print_ticket

Generate and download PDF e-ticket forms for individual tickets or complete orders, providing passengers with official travel documents required for boarding.

**Endpoint:** `https://test-api.bussystem.eu/viev/frame/print_ticket.php`  
**Method:** GET  
**Type:** Document generation function


## Description

This function generates official PDF e-ticket documents that passengers must present for boarding. The tickets feature route-specific designs and contain all necessary travel information, security codes, and barcodes for verification.

### Key Features
- PDF generation for individual tickets or complete orders
- Route-specific ticket designs and layouts
- Multi-language support for international travel
- Security codes and barcodes for verification
- Official travel documents required for boarding
- Custom check/receipt formats available

### Critical Requirements
- **Official Documents**: Passengers must present these PDF tickets for boarding
- **Complete Pages**: All pages of multi-page tickets must be provided
- **Not Receipts**: Bank receipts or payment confirmations are NOT valid for travel
- **Original Format**: Tickets should be presented in original PDF format when possible

## Parameters

### Ticket Identification (Choose One)
- **order_id** (integer, optional): Order ID to generate PDF for all tickets in the order
- **ticket_id** (integer, optional): Ticket ID to generate PDF for a single specific ticket

### Required Parameters
- **security** (string, required): Ticket or order security code for verification
- **lang** (string, required): Ticket language [en, ru, ua, de, pl, cz]

## Response Format

### Successful Response
**Content-Type:** `application/pdf`

The response is a binary PDF file containing the formatted e-ticket(s). The PDF includes:

- **Passenger Information**: Names, document details, contact information
- **Route Details**: Departure/arrival times, stations, seat assignments
- **Carrier Information**: Company details, contact numbers, policies
- **Security Features**: Barcodes, verification codes, digital signatures
- **Travel Instructions**: Boarding procedures, baggage policies, emergency contacts
- **Multi-Language Support**: All text in requested language

### PDF Structure
- **Header**: Carrier logo, route name, ticket type
- **Passenger Details**: Personal information and document data
- **Journey Information**: Complete itinerary with times and locations
- **Terms & Conditions**: Travel policies and restrictions
- **Footer**: Security codes, contact information, legal notices


## Error Responses

### Invalid ID Error
**Content-Type:** `text/html`

When order_id or ticket_id doesn't exist:

```html
<div style="padding: 20px; border: 2px solid red; background: #ffe6e6; color: #cc0000; font-family: Arial;">
  <div style="font-size: 18px; font-weight: bold; margin-bottom: 10px;">
    Error creating PDF ticket!!! Contact the ticketing agency
  </div>
  <div>Ticket ID #205232</div>
</div>
```

**Error Characteristics:**
- HTML format (not PDF)
- Error message language depends on `lang` parameter
- Includes the problematic ticket/order ID
- Styled error container for visibility


## Ticket Formats & Designs

### Standard E-Ticket
- **Layout**: Professional ticket format with carrier branding
- **Information**: Complete passenger and journey details
- **Security**: Multiple verification elements
- **Size**: Standard A4 format for printing

### Check/Receipt Format
- **Purpose**: Simplified format for terminal printing
- **Availability**: Limited to specific routes and carriers
- **Usage**: Alternative to full e-ticket (carrier-dependent)
- **Generation**: Based on data from `get_ticket` request
- **Design**: Custom layouts available upon request

### Route-Specific Designs
Different carriers and routes may have:
- **Unique Branding**: Carrier-specific colors and logos
- **Layout Variations**: Different information arrangements
- **Language Adaptations**: Region-specific formatting
- **Special Requirements**: Additional fields for international travel

## Important Guidelines

### Passenger Instructions
1. **Download Requirements**: Ensure stable internet connection for PDF download
2. **Printing Guidelines**: Print all pages in original size (no scaling)
3. **Mobile Display**: Ensure screen brightness is sufficient for barcode scanning
4. **Backup Copies**: Keep digital and printed copies as backup
5. **Document Verification**: Check all passenger details are correct before travel

### Travel Day Requirements
- **Mandatory Document**: PDF e-ticket is required for boarding
- **Complete Pages**: Present all pages of multi-page tickets
- **Readable Format**: Ensure barcodes and text are clearly visible
- **Additional ID**: Bring identification document used during booking
- **Early Arrival**: Arrive early for document verification

### Technical Considerations
- **File Size**: PDFs typically range from 100KB to 2MB depending on design
- **Download Speed**: Allow sufficient time for large PDF downloads
- **Browser Compatibility**: Modern browsers handle PDF downloads automatically
- **Mobile Storage**: Ensure adequate device storage for PDF files
- **Security**: URLs expire after extended periods for security

### Troubleshooting
- **Invalid Security Code**: Verify security code matches ticket/order
- **Expired Links**: Regenerate URLs if downloads fail
- **PDF Corruption**: Re-download if PDF appears corrupted
- **Language Issues**: Ensure correct language parameter is used
- **Missing Tickets**: Verify payment completion before attempting download


## Security Features

### PDF Authentication
- **Digital Signatures**: Verify document authenticity
- **Security Codes**: Unique codes for each ticket
- **Barcodes**: Machine-readable verification
- **Watermarks**: Visual security elements

### Access Control
- **Security Code Verification**: Prevents unauthorized access
- **Time-Limited URLs**: Links expire for security
- **Usage Tracking**: Monitor download attempts
- **Fraud Prevention**: Detect suspicious activity patterns