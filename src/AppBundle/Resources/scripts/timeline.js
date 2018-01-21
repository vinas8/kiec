import React from 'react';
import ReactDOM from 'react-dom';
import InfiniteScroll from 'react-infinite-scroller';
import qwest from 'qwest';
import moment from 'moment';
import 'moment/locale/lt';

moment.locale('lt');

class Timeline extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            lessons: [],
            hasMore: true,
            nextHref: null
        };
    }

    loadLessons() {
        let self = this,
            url = timelineUrl;

        if (this.state.nextHref) {
            url = this.state.nextHref;
        }

        qwest.get(url).then(function (xhr, resp) {
            if (resp) {
                let lessons = self.state.lessons;

                resp.collection.map((lesson) => {
                    lessons.push(lesson);
                });

                if (resp.next_href) {
                    self.setState({
                        lessons: lessons,
                        nextHref: resp.next_href
                    });
                } else {
                    self.setState({
                        hasMore: false
                    });
                }
            }
        });
    }

    render() {
        const loader = <div className="loader">Įkeliama...</div>;

        let items = [],
            labels = [];

        this.state.lessons.map((lesson, i) => {
            let mdate = moment(lesson.startTime, 'YYYY-MM-DD HH:mm::ss').format('YYYY-MM-DD');

            if (labels.indexOf(mdate) < 0) {
                labels.push(mdate);
                items.push(
                    <li key={'label' + i} className="time-label">
                        <span className="bg-red">{moment(mdate, 'YYYY-MM-DD').format('YYYY MMMM D [d.] dddd')}</span>
                    </li>
                );
            }

            items.push(
                <li key={lesson.id}>
                    <div className="timeline-item">
                        <span className="time"><i
                            className="fa fa-clock-o"> </i> {moment(lesson.startTime, 'YYYY-MM-DD HH:mm').format('LT')}
                            - {moment(lesson.endTime, 'YYYY-MM-DD HH:mm').format('LT')}</span>
                        <h3 className="timeline-header"><a href={lessonUrl + '/' + lesson.id}>{lesson.group}</a></h3>
                        <div className="timeline-footer">
                            <a href="#" data-href={lessonUrl + '/remove/' + lesson.id}
                               className="btn btn-primary btn-xs" data-toggle="modal" data-target="#confirm-delete">Atšaukti
                                pamoką</a>
                        </div>
                    </div>
                </li>
            );
        });

        return (
            <InfiniteScroll
                pageStart={0}
                loadMore={this.loadLessons.bind(this)}
                hasMore={this.state.hasMore}
                loader={loader}>

                <ul className="timeline">
                    {items}
                    <li className="time-label">
                        <i className="fa fa-clock-o"> </i>
                    </li>
                </ul>
            </InfiniteScroll>
        );
    }
}

ReactDOM.render(<Timeline/>, document.getElementById('timeline'));
