<?php $page = 'chat-doctor'; ?>
@extends('layout.mainlayout')
@section('content')
<!-- Page Content -->
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="chat-main-row">
                    <div class="chat-main-wrapper">
                        <div class="col-lg-7 message-view task-view task-left-sidebar">
                            <div class="chat-window">
                                <div class="fixed-header">
                                    <div class="navbar">
                                        <div class="user-details">
                                            <div class="float-start user-img">
                                                <a class="avatar avatar-sm me-2" href="profile.html">
                                                    <img class="rounded-circle" alt="" src="{{ URL::asset('assets/img/doctors/doctor-thumb-02.jpg') }}">
                                                    <span class="status online"></span>
                                                </a>
                                            </div>
                                            <div class="user-info float-start">
                                                <a href="profile.html"><span>Dr. Darren Elder</span></a>
                                                <span class="last-seen">Online</span>
                                            </div>
                                        </div>
                                        <ul class="nav float-end custom-menu">
                                            <li class="nav-item">
                                                <a class="nav-link" href="#task_window"><i class="fa fa-comments"></i></a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#chat_sidebar"><i class="fa fa-ellipsis-v"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="chat-contents">
                                    <div class="chat-content-wrap">
                                        <div class="chat-wrap-inner">
                                            <div class="chat-box">
                                                <div class="chats">
                                                    <div class="chat chat-left">
                                                        <div class="chat-avatar">
                                                            <a href="profile.html" class="avatar">
                                                                <img alt="" src="{{ URL::asset('assets/img/patients/patient.jpg') }}">
                                                            </a>
                                                        </div>
                                                        <div class="chat-body">
                                                            <div class="chat-bubble">
                                                                <div class="chat-content">
                                                                    <p>Hello. What can I do for you?</p>
                                                                    <span class="chat-time">8:30 am</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="chat chat-left">
                                                        <div class="chat-avatar">
                                                            <a href="profile.html" class="avatar">
                                                                <img alt="" src="{{ URL::asset('assets/img/patients/patient.jpg') }}">
                                                            </a>
                                                        </div>
                                                        <div class="chat-body">
                                                            <div class="chat-bubble">
                                                                <div class="chat-content">
                                                                    <p>I'm just looking around.</p>
                                                                    <span class="chat-time">8:31 am</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="chat chat-left">
                                                        <div class="chat-avatar">
                                                            <a href="profile.html" class="avatar">
                                                                <img alt="" src="{{ URL::asset('assets/img/patients/patient.jpg') }}">
                                                            </a>
                                                        </div>
                                                        <div class="chat-body">
                                                            <div class="chat-bubble">
                                                                <div class="chat-content">
                                                                    <p>When will I receive my order?</p>
                                                                    <span class="chat-time">8:34 am</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="chat">
                                                        <div class="chat-avatar">
                                                            <a href="profile.html" class="avatar">
                                                                <img alt="" src="{{ URL::asset('assets/img/doctors/doctor-thumb-02.jpg') }}">
                                                            </a>
                                                        </div>
                                                        <div class="chat-body">
                                                            <div class="chat-bubble">
                                                                <div class="chat-content">
                                                                    <p>Your order will be delivered between 10 AM - 11 AM.</p>
                                                                    <span class="chat-time">8:35 am</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="chat chat-left">
                                                        <div class="chat-avatar">
                                                            <a href="profile.html" class="avatar">
                                                                <img alt="" src="{{ URL::asset('assets/img/patients/patient.jpg') }}">
                                                            </a>
                                                        </div>
                                                        <div class="chat-body">
                                                            <div class="chat-bubble">
                                                                <div class="chat-content">
                                                                    <p>Okay. Thanks for the info.</p>
                                                                    <span class="chat-time">8:36 am</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="chat-footer">
                                    <div class="input-group">
                                        <input class="form-control" placeholder="Type message here" type="text">
                                        <div class="input-group-prepend">
                                            <button type="button" class="btn btn-primary btn-send rounded-circle"><i class="fa fa-paper-plane"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 message-view task-chat-view task-right-sidebar" id="task_window">
                            <div class="chat-window">
                                <div class="fixed-header">
                                    <div class="navbar">
                                        <div class="task-assign">
                                            <span class="assign-title">Assigned to</span>
                                            <a href="#" data-bs-toggle="tooltip" data-placement="bottom" title="John Doe" class="avatar">
                                                <img alt="" src="{{ URL::asset('assets/img/doctors/doctor-thumb-02.jpg') }}">
                                            </a>
                                            <a href="#" class="followers-add" title="Add Assignee" data-bs-toggle="modal" data-bs-target="#task_followers">
                                                <i class="fa fa-plus"></i>
                                            </a>
                                        </div>
                                        <ul class="nav float-end custom-menu">
                                            <li class="nav-item dropdown dropdown-action">
                                                <a href="" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-cog"></i></a>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="javascript:void(0);">Pending Tasks</a>
                                                    <a class="dropdown-item" href="javascript:void(0);">Completed Tasks</a>
                                                    <a class="dropdown-item" href="javascript:void(0);">All Tasks</a>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="chat-contents task-chat-contents">
                                    <div class="chat-content-wrap">
                                        <div class="chat-wrap-inner">
                                            <div class="chat-box">
                                                <div class="chats">
                                                    <h4>Patient List</h4>
                                                    <div class="chat chat-left">
                                                        <div class="chat-avatar">
                                                            <a href="profile.html" class="avatar">
                                                                <img alt="" src="{{ URL::asset('assets/img/patients/patient.jpg') }}">
                                                            </a>
                                                        </div>
                                                        <div class="chat-body">
                                                            <div class="chat-bubble">
                                                                <div class="chat-content">
                                                                    <h6>Patient Name</h6>
                                                                    <p>Last message preview...</p>
                                                                    <span class="chat-time">2 mins ago</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Page Content -->
@endsection

