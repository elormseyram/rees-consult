<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e1e8ed; border-radius: 8px; background-color: #ffffff; }
        .header { background-color: #0A1E42; color: white; padding: 15px; border-radius: 6px 6px 0 0; text-align: center; }
        .header h2 { margin: 0; font-size: 20px; font-weight: bold; }
        .content { padding: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .table th, .table td { padding: 10px; border-bottom: 1px solid #e1e8ed; text-align: left; vertical-align: top; }
        .table th { background-color: #f4f6f9; color: #0A1E42; font-weight: bold; width: 35%; }
        .footer { margin-top: 20px; font-size: 12px; color: #888; text-align: center; border-top: 1px solid #e1e8ed; padding-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>New Service Submission Notification</h2>
        </div>
        <div class="content">
            <p>Hello Rees Consult Team,</p>
            <p>A new form has been submitted on the website. Here are the details:</p>
            
            <table class="table">
                <tr>
                    <th>Form Type</th>
                    <td>
                        @if($type === 'signup')
                            Service Prep Course Registration (Standardized Test)
                        @elseif($type === 'school')
                            School Application
                        @elseif($type === 'job')
                            Job Placement Application (Jobs Abroad)
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>First Name</th>
                    <td>{{ $submission->first_name }}</td>
                </tr>
                <tr>
                    <th>Last Name</th>
                    <td>{{ $submission->last_name }}</td>
                </tr>
                <tr>
                    <th>Email Address</th>
                    <td><a href="mailto:{{ $submission->email }}">{{ $submission->email }}</a></td>
                </tr>
                <tr>
                    <th>Phone Number</th>
                    <td><a href="tel:{{ $submission->phone }}">{{ $submission->phone }}</a></td>
                </tr>
                
                @if($type === 'signup')
                    <tr>
                        <th>Selected Service</th>
                        <td>{{ $submission->service->title ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Preferred Class Format</th>
                        <td>{{ ucwords(str_replace('_', ' ', $submission->preferred_class_type)) }}</td>
                    </tr>
                @elseif($type === 'school')
                    <tr>
                        <th>Course of Interest</th>
                        <td>{{ $submission->course_of_interest }}</td>
                    </tr>
                    <tr>
                        <th>Level of Education</th>
                        <td>{{ ucwords($submission->level_of_education) }}</td>
                    </tr>
                    <tr>
                        <th>Target Countries</th>
                        <td>{{ $submission->target_countries }}</td>
                    </tr>
                    <tr>
                        <th>Highest Qualification</th>
                        <td>{{ $submission->highest_qualification }}</td>
                    </tr>
                    <tr>
                        <th>Has Passport</th>
                        <td>{{ $submission->has_passport ? 'Yes' : 'No' }}</td>
                    </tr>
                    <tr>
                        <th>Budget</th>
                        <td>{{ $submission->budget }}</td>
                    </tr>
                    @if($submission->resume_path)
                        <tr>
                            <th>Resume Path</th>
                            <td><a href="{{ asset('storage/' . $submission->resume_path) }}" target="_blank">View Uploaded Resume</a></td>
                        </tr>
                    @endif
                    @if($submission->transcript_path)
                        <tr>
                            <th>Transcript Path</th>
                            <td><a href="{{ asset('storage/' . $submission->transcript_path) }}" target="_blank">View Uploaded Transcript</a></td>
                        </tr>
                    @endif
                @elseif($type === 'job')
                    <tr>
                        <th>Selected Job Role</th>
                        <td>{{ $submission->service->title ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Target Country</th>
                        <td>{{ $submission->service->country ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Work Experience (Years)</th>
                        <td>{{ $submission->experience_years }}</td>
                    </tr>
                    <tr>
                        <th>Current Occupation</th>
                        <td>{{ $submission->current_occupation }}</td>
                    </tr>
                    <tr>
                        <th>Highest Education</th>
                        <td>{{ $submission->highest_education }}</td>
                    </tr>
                    @if($submission->resume_path)
                        <tr>
                            <th>Resume Path</th>
                            <td><a href="{{ asset('storage/' . $submission->resume_path) }}" target="_blank">View Uploaded Resume</a></td>
                        </tr>
                    @endif
                @endif
                
                @if($submission->notes)
                    <tr>
                        <th>Notes / Extra Requests</th>
                        <td>{{ $submission->notes }}</td>
                    </tr>
                @endif
            </table>
        </div>
        <div class="footer">
            <p>This email was automatically generated by the Rees Consult platform.</p>
        </div>
    </div>
</body>
</html>
