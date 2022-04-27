'use strict';

class MainComponent extends React.Component {
    componentWillMount() {

        this.setState({
            latitude: 0,
            longitude: 0,
            service_status: [],
            account_status: [],
            request_filter: {},
            request: {
                user: {
                    picture: '/asset/logo.png',
                    first_name: 'John',
                    last_name: 'Doe'
                },
            }
        });

        setInterval(
            () => this._requestPoll(),
            3000
        );
    }

    componentDidMount() {
        this._requestPoll();
    }

    _requestPoll(){
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                this.setState({latitude: position.coords.latitude, longitude: position.coords.longitude});
            }.bind(this));
        }

        $.ajax({
            url: '/provider/incoming',
            dataType: "JSON",
            headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
            data: {
                latitude: this.state.latitude,
                longitude: this.state.longitude
            },
            type: "GET",
            success: function(data){
                // this.setState({account_status: data.account_status});
                // this.setState({service_status: data.service_status});
                if(data.requests.length > 0) {
                    // console.log('data.requests[0]', data.requests[0].request);
                    this.setState({request: data.requests[0].request});
                    this.setState({request_filter: data.requests[0]});
                    updateChatParam(data.requests[0].request.id, data.requests[0].request.user.id);
                }else{
                    if($('#incoming').is(':visible')) {
                        window.location.replace("/provider");
                    }
                    this.setState({account_status: data.account_status, service_status: data.service_status});

                }
            }.bind(this)
        });
    }
    render() {

        if(this.props.trip == "true") {
            var location = { latitude: this.state.latitude, longitude: this.state.longitude };
            return (
                <div> 
                    <ModalComponent request_filter={this.state.request_filter} request={this.state.request}  />
                    <TripComponent request={this.state.request} service_status={this.state.service_status} account_status={this.state.account_status} location={location} />
                </div>
            );
        } else {
            return (
                <div> 
                    <ModalComponent request={this.state.request} />
                </div>
            );
        }
    }
};

class ModalComponent extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            bidding: 0
        };

        this.handleBiddingChange = this.handleBiddingChange.bind(this);
    }
    handleBiddingChange(event) {
        this.setState({bidding: event.target.value});
    }

    componentDidUpdate(prevProps, prevState) {
        var requestData = this.props.request;
        var request_filter = this.props.request_filter;


        if(this.props.request.status == "SEARCHING") {
            this._open();
        }else if(this.props.request.status == "BIDDING" && request_filter.status == 0){
            this._open();
        }
    }

    _accept(event) {
        event.preventDefault();

        $.ajax({
            url: '/provider/request/'+this.props.request.id,
            dataType: 'json',
            headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
            type: 'POST',
            success: function(data) {
                console.log('Accept', data);
                if(data.error == undefined) {
                    window.location.replace("/provider");
                }
                this._close();
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
        this._close();
    }


    _submit(event) {
        event.preventDefault();

        $.ajax({
            url: '/provider/request/bidding/'+this.props.request.id,
            dataType: 'json',
            data: this.state,
            headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
            type: 'POST',
            success: function(data) {
                console.log('Submit', data);
                if(data.error == undefined) {
                    window.location.replace("/provider");
                }
                this._close();
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
        this._close();
    }

    _reject(event) {
        event.preventDefault();

        $.ajax({
            url: '/provider/request/'+this.props.request.id,
            dataType: 'json',
            type: 'DELETE',
            headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
            success: function(data) {
                console.log('Reject', data);
                if(data.error == undefined) {
                    window.location.replace("/provider");
                }
                this._close();
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
        this._close();
    }

    _open() {
        $("#incoming").modal('show');
    }

    _close() {
        $("#incoming").hide('hide');
    }

    render() {
        return (
            <div className="modal fade" id="incoming" role="dialog">
                <div className="modal-dialog" role="document">
                    <div className="modal-content">
                        <div className="modal-header">
                            <h4 className="modal-title text-center incoming-tit" id="myModalLabel">Incoming Request</h4>
                        </div>
                        <div className="modal-body">
                            <div className="incoming-img bg-img" id="user-image" style={{ backgroundImage: 'url(' + this.props.request.user.picture + ')' }}></div>
                            <div className="text-center">
                                <h3 id="usser-name">{this.props.request.user.first_name} {this.props.request.user.last_name}</h3>
                                <h4 id="service_items">User Service Item :{this.props.request.service_items}</h4>
                                {this.props.request.schedule_at ?
                                    <h5>Scheduled At : {this.props.request.schedule_at}</h5>
                                    : ""
                                }
                                <h5>Pickup Address : {this.props.request.d_address}</h5>

                                {
                                    this.props.request.userdrop != null ?

                                    this.props.request.userdrop.map(function(record, index) {

                                        return <dl className="dl-horizontal left-right">
                                        <input type="hidden" className="Dropid" value={record.id} />
                                        <dt>Reciever Name</dt>
                                        <dd>{record.reseiver_name}</dd>
                                        <dt>Reciever Mobile</dt>
                                        <dd>{record.reseiver_mobile}</dd>
                                        <dt>Delivery Address</dt>
                                        <dd>{record.d_address}</dd>
                                        </dl>
                                    })
                                    :
                                    ""
                                }
                              
                            </div>
                            <input type="number" className="form-control" name="bidding"  id="bidding" value={this.state.bidding} onChange={this.handleBiddingChange} placeholder="Enter the amount" />
                        </div>
                        <div className="modal-footer row no-margin">
                            <button type="button" className="btn btn-primary incoming-btn" onClick={this._submit.bind(this)}>Submit</button>
                            <button type="button" className="btn btn-default incoming-btn" onClick={this._reject.bind(this)} data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
};

ModalComponent.defaultProps = {
    request: {
        user: {
            first_name: "John",
            last_name: "Doe",
            picture: "/asset/logo.png",
        }
    }
};

class TripEmptyActive extends React.Component {
    componentDidMount(){
        initMap();
    }
    render() {
        return (
            <div className="row no-margin">
                <div className="col-md-12">
                    <form method="POST" action="provider/profile/available">
                        <input type="hidden" value="offline" name="service_status"/>
                        <div id="map" style={{ width: '100%', height: '425px' }}></div>
                        <button type="submit" className="full-primary-btn fare-btn">GO OFFLINE</button>
                    </form>
                </div>
            </div>
        );
    }
};

class TripEmptyOffline extends React.Component {
    render() {
        return (
            <div className="row no-margin">
                <div className="col-md-12">
                    <form method="POST" action="provider/profile/available">
                        <input type="hidden" value="active" name="service_status"/>
                        <div className="offline">
                            <img src="/asset/img/offline.gif"/>
                        </div>
                        <button type="submit" className="full-primary-btn fare-btn">GO ONLINE</button>
                    </form>
                </div>
            </div>
        );
    }
};

class TripArrivedButton extends React.Component {
    
    constructor(props) {
        super(props);
        this.state = {
            reason: ''
        };

        this.handleCancelReason = this.handleCancelReason.bind(this);
    }

    handleCancelButton(event) {
        this.props.cancel(this.state.reason);
    }

    handleCancelReason(event) {
        this.setState({reason: event.target.value});
    }

    render() {
        return (
            <div>
                <button type="submit" className="full-primary-btn fare-btn" onClick={this.props.submit.bind(this)}>Arrived</button>
                <div id="cancel-reason" className="modal fade" role="dialog">
                    <div className="modal-dialog">
                        <div className="modal-content">
                            <div className="modal-header">
                                <button type="button" className="close" data-dismiss="modal">&times;</button>
                                <h4 className="modal-title">Cancel Reason</h4>
                            </div>
                            <div className="modal-body">
                                <textarea className="form-control" onChange={this.handleCancelReason} name="cancel_reason" placeholder="Cancel Reason">{this.state.reason}</textarea>
                            </div>
                            <div className="modal-footer">
                                <button type="submit" className="full-primary-btn fare-btn reg-btn" onClick={this.handleCancelButton.bind(this)}>Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" className="full-primary-btn fare-btn reg-btn" data-toggle="modal" data-target="#cancel-reason">Cancel</button>
            </div>
        );
    }    
}

class TripPickedButton extends React.Component {
    render() {
        return (
            <div>
                <button type="submit" className="full-primary-btn fare-btn" onClick={this.props.submit.bind(this)}>Picked Up</button>
            </div>
        );
    }
}

class TripDroppedButton extends React.Component {
    render() {
        return (
            <div>
                <button type="submit" className="full-primary-btn fare-btn" onClick={this.props.submit.bind(this)}>Dropped</button>
            </div>
        );
    }
}

class TripCompletedButton extends React.Component {
    render() {
        return (
            <div>
                <button type="submit" className="full-primary-btn fare-btn" onClick={this.props.submit.bind(this)}>Paid</button>
            </div>
        );
    }
}

class BiddingComponent extends React.Component {
    render() {
        return (
            <div>
                <h3>Waiting for admin approval</h3>
            </div>
        );
    }
}

class TripRatingButton extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            rating: '1',
            comment: ''
        };

        this.handleRatingChange = this.handleRatingChange.bind(this);
        this.handleCommentChange = this.handleCommentChange.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    handleRatingChange(event) {
        this.setState({rating: event.target.value});
    }

    handleCommentChange(event) {
        this.setState({comment: event.target.value});
    }

    handleSubmit(event) {
        event.preventDefault();
    }

    componentDidMount() {
        $('.rating').rating();
    }

    _submit(event) {
        event.preventDefault();

        $.ajax({
            url: '/provider/request/'+this.props.request+'/rate',
            dataType: 'json',
            data: {
                comment: $("#ratecmt").val(),
                rating: $("#rateip").val(),
            },
            headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
            type: 'POST',
            success: function(data) {
                window.location.replace("/provider");
                console.log('Accept', data);
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    }

    render() {
        return (
            <div>
                <div className="rate-review">
                    <label>Rating</label>
                    <div className="rating-outer">
                        <input type="hidden"  id="rateip" name="rating" className="rating" onChange={this.handleRatingChange} />
                    </div>
                    <label>Your Comments</label>
                    <textarea className="form-control" name="comment"  id="ratecmt" value={this.state.comment} onChange={this.handleCommentChange} placeholder="Write Comment" />
                </div>
                <button type="submit" className="full-primary-btn fare-btn" onClick={this._submit.bind(this)}>SUBMIT REVIEW</button>   
            </div>
        );
    }
}

class TripDetails extends React.Component {
    componentDidMount() {
        initMap();
        initChat();
    }

    render() {
        let picture = "/asset/logo.png";
        // console.log("this.props.request.user.picture", this.props.request.user.picture);
        if(this.props.request.user.picture != null) {
            picture = this.props.request.user.picture;
        }
        return (
            <div className="row no-margin">
                <div className="col-md-6">
                    <div className="provider-info">
                        <div className="img" style={{ backgroundImage: 'url(' + picture + ')'}}></div>
                        <div className="content">
                            <h6>{this.props.request.user.first_name} {this.props.request.user.last_name}</h6>
                            <div className="rating-outer">
                                <input type="hidden" className="rating" value={this.props.request.user.rating} />
                            </div>
                        </div>
                    </div>
                    <br />
                    <dl className="dl-horizontal left-right">
                        <dt>Request ID</dt>
                        <dd>{this.props.request.id}</dd>
                        <dt>Payment Mode</dt>
                        <dd>{this.props.request.payment_mode}</dd>
                    </dl>

                    {

                          

                           this.props.request.userdrop != null ?
                          
                            this.props.request.userdrop.map(function(record, index) {
                                    
                                  
                                    return <dl className="dl-horizontal left-right">
                                     <input type="hidden" className="Dropid" value={record.id} />
                                      <dt>Reciever Name</dt>
                                    <dd>{record.reseiver_name}</dd>
                                    <dt>Reciever Mobile</dt>
                                    <dd>{record.reseiver_mobile}</dd>

                                    <dt>Delivery Address</dt>
                                    <dd>{record.d_address}</dd>
                                    

                                    </dl>

                             })

                             :
                              ""
                           }


                    {this.props.button}
                      
                </div>
                <div className="col-md-6">
                    <div className="user-request-map">
                        <div className="from-to row no-margin">
                            <div className="from">
                                <h5>FROM</h5>
                                <p>{this.props.request.s_address}</p>
                            </div>
                            <div className="to">
                                <h5>TO</h5>
                                <p>{this.props.request.d_address}</p>
                            </div>
                        </div>
                        <div className="map-responsive-trip" id="map"></div>
                    </div>
                </div>
            </div>
        );
    }
}

class ServiceDetails extends React.Component {
    componentDidMount() {
        initMap();
        initChat();

    }


   
    render() {
        let picture = "/asset/logo.png";
        // console.log("this.props.request.user.picture", this.props.request.user.picture);
        if(this.props.request.user.picture != null) {
            picture = this.props.request.user.picture;
        }
        return (
            <div className="row no-margin">
                <div className="col-md-6">
                    <div className="provider-info">
                        <div className="img" style={{ backgroundImage: 'url(' + picture + ')'}}></div>
                        <div className="content">
                            <h6>{this.props.request.user.first_name} {this.props.request.user.last_name}</h6>
                            <div className="rating-outer">
                                <input type="hidden" className="rating" value={this.props.request.user.rating} />
                            </div>
                        </div>
                    </div>
                    <br />
                    <dl className="dl-horizontal left-right">
                        <dt>Request ID</dt>
                        <dd>{this.props.request.id}</dd>
                        <dt>Payment Mode</dt>
                        <dd>{this.props.request.payment_mode}</dd>
                    </dl>

                    {

                          

                           this.props.request.userdrop != null ?
                          
                            this.props.request.userdrop.map(function(record, index) {
                                    
                
                                    return <dl className="dl-horizontal left-right">
                                     <input type="hidden" className="Dropid" value={record.id} />
                                      <dt>Reciever Name</dt>
                                    <dd>{record.reseiver_name}</dd>
                                    <dt>Reciever Mobile</dt>
                                    <dd>{record.reseiver_mobile}</dd>
                                    <dt>Delivery Address</dt>
                                    <dd>{record.d_address}</dd>
                                    {
                                    record.status == "SEARCHING" ?
                                    <form method="GET" encType="multipart/form-data" action="provider/request/service">
                                      <input type="hidden" className="Dropid" name="id" value={record.id} />
                                      <input type="hidden" className="requestid" name="requestid" value={record.user_request_id} />
                                    <input type="hidden" className="status" name="status" value={record.status} />
                                       <button type="submit" data-id={record.id}  className="full-primary-btn fare-btn start_service" name="start_service">Start Delivery</button>
                                       </form>
                                       :
""
                                    
                                    }
                                    {
                                         record.status == "STARTED" ?
                                        <form method="GET" encType="multipart/form-data" action="provider/request/service">
                                      <input type="hidden" className="Dropid" name="id" value={record.id} />
                                       <input type="hidden" className="requestid" name="requestid" value={record.user_request_id} />
                                      <input type="hidden" className="status" name="status" value={record.status} />
                                        <input type="hidden" name="_method" value="PATCH"  />
                                        <button type="submit" data-id={record.id}  className="full-primary-btn fare-btn end_service">ARRIVED</button>
                                        </form>
                                        :
""
                                    }
                                    {
                                         record.status == "DROPPED" ?
                                        <form method="POST" encType="multipart/form-data" action="provider/request/service">
                                      <input type="hidden" className="Dropid" name="id" value={record.id} />
                                       <input type="hidden" className="requestid" name="requestid" value={record.user_request_id} />
                                      <input type="hidden" className="status" name="status" value={record.status} />
                                      <label>Image</label>
                                      <input type="file" name="after_image" className="form-control" id="after_image" required/>
                                              <br/>
                                        <input type="hidden" name="_method" value="PATCH"  />
                                        <input type="hidden" name="mode" value="1"/>
                                        <button type="submit" data-id={record.id}  className="full-primary-btn fare-btn end_service">End Delivery</button>
                                        </form>
                                        :
""
                                    }
                                    {
                                         record.status == "COMPLETED" ?
                                        
                                        
                                        <button type="submit"  className="full-primary-btn fare-btn completed_service">Delivered</button>
                                       
                                        :
                                        ""
                                    }
                                
                                </dl>

                             })

                             :
                              ""
                           }
                
                    {this.props.button}

                </div>
                <div className="col-md-6">
                    <div className="user-request-map">
                        <div className="from-to row no-margin">
                            <div className="from">
                                <h5>FROM</h5>
                                <p>{this.props.request.s_address}</p>
                            </div>
                            <div className="to">
                                <h5>TO</h5>
                                <p>{this.props.request.d_address}</p>
                            </div>
                        </div>
                        <div className="map-responsive-trip" id="map"></div>
                    </div>
                </div>
            </div>
        );
    }
}

class TripComponent extends React.Component {

    componentDidUpdate() {
        // console.log('Trip Component '+this.props.request.id);
        // console.log('Trip Component '+this.props.service_status);
        switch(this.props.request.status) {
            case "STARTED":
                this.form= {
                        status: "ARRIVED",
                        _method: "PATCH",
                    };

                updateMap({
                    source: {
                        lat: this.props.location.latitude,
                        lng: this.props.location.longitude,
                    },
                    destination: {
                        lat: this.props.request.s_latitude,
                        lng: this.props.request.s_longitude,
                    }
                });
                break;
            case "ARRIVED": 
                this.form= {
                        status: "PICKEDUP",
                        _method: "PATCH",
                    };

                updateMap({
                    source: {
                        lat: this.props.request.s_latitude,
                        lng: this.props.request.s_longitude,
                    },
                    destination: {
                        lat: this.props.request.d_latitude,
                        lng: this.props.request.d_longitude,
                    }
                });

                break;
            case "PICKEDUP": 
                this.form= {
                        status: "DROPPED",
                        _method: "PATCH",
                    };

                updateMap({
                    source: {
                        lat: this.props.request.s_latitude,
                        lng: this.props.request.s_longitude,
                    },
                    destination: {
                        lat: this.props.request.d_latitude,
                        lng: this.props.request.d_longitude,
                    }
                });

                break;
            case "DROPPED": 
                this.form= {
                        status: "COMPLETED",
                        _method: "PATCH",
                    };
                break;
            default:
                break;
        }
    }

    _submit(event) {
        event.preventDefault();
        $.ajax({
            url: '/provider/request/'+this.props.request.id,
            dataType: 'json',
            data: this.form,
            headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken, 'X-Requested-With' : 'XMLHttpRequest' },
            type: 'POST',
            success: function(data) {
                console.log('Updated', data);
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    }

    _cancel(reason) {
        // event.preventDefault();

        $.ajax({
            url: '/provider/cancel',
            dataType: 'json',
            data: {cancel_reason:reason, id: this.props.request.id},
            headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
            type: 'POST',
            success: function(data) {
                window.location.replace("/provider");
            }.bind(this),
            error: function(xhr) {
                window.location.replace("/provider");
            }.bind(this)
        });
    }

    getButtons() {
        switch(this.props.request.status) {
            case "STARTED": 
                return (
                    <TripArrivedButton submit={this._submit.bind(this)} cancel={this._cancel.bind(this)} />
                );
                break;
            case "ARRIVED": 
                return (
                    <TripPickedButton submit={this._submit.bind(this)} />
                );
                break;
            case "PICKEDUP": 
                return (
                    <TripDroppedButton submit={this._submit.bind(this)} />
                );
                break;
            case "DROPPED": 
                return (
                    (this.props.request.status == 'DROPPED' && this.props.request.payment_mode != 'CASH' && this.props.request.payment_mode != 'BOL')
                    ?
                    <TripRatingButton request={this.props.request.id} />
                    :
                    <TripCompletedButton submit={this._submit.bind(this)} />
                );
                break;
            case "COMPLETED": 
                return (
                    <TripRatingButton request={this.props.request.id} />
                );
                break;

           
            default:
                return null;
        }
    }

    serviceButtons() {
        switch(this.props.request.status) {
            case "STARTED": 
                return (
                    <TripArrivedButton submit={this._submit.bind(this)} cancel={this._cancel.bind(this)} />
                );
                break;
            case "ARRIVED": 
                return (
                    <TripPickedButton submit={this._submit.bind(this)} />
                );
                break;
            case "PICKEDUP": 
                return (
                    <TripDroppedButton submit={this._submit.bind(this)} />
                );
                break;
            case "DROPPED": 
                return (
                    <TripCompletedButton submit={this._submit.bind(this)} />
                );
                break;
            case "COMPLETED": 
                return (
                    <TripRatingButton request={this.props.request.id} />
                );
                break;
            default:
                return null;
        }
    }


    render() {

        if(this.props.request.id == undefined) {
            if(this.props.service_status == 'active'){
                return (
                    <TripEmptyActive />
                    );
            }else{
                return (
                    <TripEmptyOffline />
                    );
            }
        }
        else {
            var requestData = this.props.request;
            var providerIds = [];
            if (requestData.biddingprovider.length > 0) {
                providerIds = this.props.request.biddingprovider.filter(function(item) {
                    return item.provider_id == requestData.current_provider_id;
                });
            }
            if(this.props.request.status == 'ARRIVED'){
                return (
                    <ServiceDetails request={this.props.request} submit={this.handleSubmit}  />
                    );
            }else if(this.props.request.status == 'BIDDING' ){
                return (
                    <BiddingComponent request={this.props.request.id} />
                    );
            }else{
                console.log('Rendering Trip Details');
                return (
                    <TripDetails request={this.props.request} button={this.getButtons()} />
                    );
            }
        }
    }
};

ReactDOM.render(
    <ModalComponent />,
    document.getElementById('modal-incoming')
);

if(document.getElementById('trip-container')) {
    console.log('Rendering to Trip Container');
    ReactDOM.render(
        <MainComponent trip="true" />,
        document.getElementById('trip-container')
    );
} else {
    console.log('Rendering to Modal Container');
    ReactDOM.render(
        <MainComponent trip="false" />,
        document.getElementById('modal-incoming')
    );
}