
// For defination check below example
//======================================

// NB: The three phases are: Mounting, Updating, and Unmounting.

// getDerivedStateFromProps : (static) The getDerivedStateFromProps() method is called right before rendering the element(s) in the DOM.
// componentDidMount        : When DOM will be ready
// getSnapshotBeforeUpdate  : Before update the state
// componentDidUpdate       : When State will be updated
// shouldComponentUpdate    : If it return false, The component won't render and update the state.
// componentWillUnmount     : When this component (<UnMountTest />) will be unmounted

import React, { Component } from 'react';

class App extends Component {
  constructor(props) {
    super(props);
  
    this.state = { 
      count:1
    };
  }

  static getDerivedStateFromProps(props, state) {
    console.log('getDerivedStateFromProps')
    
    return null;
  }

  componentDidMount() {
    setInterval(() => {
      this.setState({ count: this.state.count + 1 })
      console.log('componentDidMount')
    }, 1000)
  }

  getSnapshotBeforeUpdate(prevProps, prevState) {
    console.log(prevState)
    console.log('getSnapshotBeforeUpdate');
    return null;
  }

  componentDidUpdate() {
    console.log('componentDidUpdate')
  }

  shouldComponentUpdate() {
    return false;
  }


  render() {
    return (
      <div>
        <h1>My Favorite Color is {this.state.count}</h1>
        {/* This <UnMountTest/> component will unmount after 3 second */}
        {this.state.count < 4 &&
          <UnMountTest/>
        }
      </div>
    );
  }
}

export default App;


class UnMountTest extends React.Component {
  componentWillUnmount() {
    console.log('componentWillUnmount')
    alert("The component named Header is about to be unmounted.");
  }
  render() {
    return (
      <h1>This is (componentWillUnmount) Example. This component will unmount after 3 second. </h1>
    );
  }
}
