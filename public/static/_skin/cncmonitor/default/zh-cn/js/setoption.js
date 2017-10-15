var maxBar=30;
var powerData;
var formatterFUn=function(data){return (data.data/60/60).toFixed(1)+'H';}
var tmpOption={
    tooltip:{
        trigger: 'axis',
        axisPointer : {
            type : 'line'
        },
		formatter:function(data){
			var html='';
			for(i in data){
				html+='<br /><b style="color:'+data[i].color+'">●</b>&nbsp;'+data[i].seriesName+'：'+jsTime(data[i].data);
			}
			html=data[0].name+html;
			return html;
		},
    },
	legend: {
        data:[],
    },
	grid: {
        left: '3%',
        right: '4%',
        bottom: '3%',
        containLabel: true
    },
	xAxis : [
        {
            type : 'category',
            data : []
        }
    ],
    yAxis : [
        {
            type : 'value',
			axisLabel:{
				formatter:function(v){
					return Math.round(v/60/60)+'H';
				}
			}
        }
    ],
	series : [],
};
var tmpLabel={
	normal:{
		show:true,
		position:'inside',
		formatter:formatterFUn,
	}
};
var evtFun={
	'bar':function(params) {
		var but=$('#chartType_tu button[data-type="bar"]');
		var tmpOP={
			series:[{
				name:'开机',
				type:'line',
				data:powerData,
				label:{
					normal:{
						show:true,
						position:'top',
						formatter:formatterFUn,
					}
				},
				itemStyle:{
					normal:{
						color:opArr.power.color,
					},
				}
			}],
		}
		if(params.selected[opArr.run.name]==false && params.selected[opArr.warning.name]==false && params.selected[opArr.standby.name]==false){
			if(parseInt(but.attr('data-less'))==2){
				tmpOP.series[0].areaStyle={normal: {}};
				tmpOP.series[0].smooth=true;
			}else{
				tmpOP.series[0].type='bar';
				tmpOP.zlevel=1;
			}
			myChart.setOption(tmpOP);
		}else if(params.selected[opArr.run.name]==false || params.selected[opArr.warning.name]==false || params.selected[opArr.standby.name]==false){
			tmpOP.series[0].label.normal.show=false;
			if(parseInt(but.attr('data-less'))==2){
				tmpOP.series[0].areaStyle={normal: {}};
				tmpOP.series[0].smooth=true;
			}
			myChart.setOption(tmpOP);
		}else{
			tmpOP.series[0].type='scatter';
			tmpOP.zlevel=3;
			myChart.setOption(tmpOP);
		}
		var tmpArr=['run','warning','standby','task'];
		var zz=0;
		var tn;
		for(i in tmpArr){
			if(params.selected[opArr[tmpArr[i]].name]){
				zz++;
				tn=opArr[tmpArr[i]].name;
			}
		}
		tmpOP={
			series:[{
				name:'',
				label:{
					normal:{
						show:true,
					}
				}
			}],
		}
		if(zz==1){
			tmpOP.series[0].name=tn;
			myChart.setOption(tmpOP);
		}else{
			tmpOP.series[0].label.normal.show=false;
			for(i in tmpArr){
				tmpOP.series[0].name=opArr[tmpArr[i]].name;
				myChart.setOption(tmpOP);
			}
		}
	},
}
var opFun={
	'bar':function(data){
		var ts=Object.getOwnPropertyNames(data).length;
		var but=$('#chartType_tu button[data-type="bar"]');
		if(ts>maxBar){
			but.attr('data-less',2);
			but.text('趋势图');
		}else{
			but.attr('data-less',1);
			but.text('柱状图');
		}
		var backOp=clone(tmpOption);
		var tmpSeries={};
		for(i in data){
			backOp.xAxis[0].data.push(i);
			var dd=clone(data[i][0]);
			delete dd.id;
			for(n in dd){
				if(!tmpSeries.hasOwnProperty(n)){
					tmpSeries[n]={
						name:opArr[n].name,
						type:'bar',
						zlevel:2,
						data:[],
						label:{
							normal:{
								show:false,
								position:'top',
								formatter:formatterFUn,
							},
						},
						itemStyle:{
							normal:{
								color:opArr[n].color,
							},
						}
					}
					if(n=='power' || n=='task'){
						if(n=='task'){
							tmpSeries[n].type='line';
							tmpSeries[n].label.normal.show=false;
							tmpSeries[n].areaStyle={normal: {}};
							tmpSeries[n].smooth=true;
						}else{
							tmpSeries[n].type='scatter';
							tmpSeries[n].label.normal.show=true;
						}
						tmpSeries[n].label.normal.position='top';
					}else{
						tmpSeries[n].type=ts>maxBar?'line':'bar';
						if(ts>maxBar){
							tmpSeries[n].areaStyle={normal: {}};
							tmpSeries[n].smooth=true;
						}
						tmpSeries[n].stack='机床';
					}
				}
				tmpSeries[n].data.push(dd[n]);
			}
			
		}
		powerData=tmpSeries.power.data;
		for(i in tmpSeries){
			backOp.series.push(tmpSeries[i]);
			backOp.legend.data.push(opArr[i].name);
		}
		//console.log(backOp);
		return backOp;
	},
	'pie':function(data){
		var ts=Object.getOwnPropertyNames(data).length;
		ts=ts*24*60*60;
		var backOp=clone(tmpOption);
		backOp.tooltip={
			trigger: 'item',
			formatter: function(data){
				return data.name+'<br />'+data.percent+'%<br />'+jsTime(data.value);
			}
		};
		backOp.legend={
			orient: 'vertical',
			left: 'left',
			data: [],
		}
		delete backOp.grid;
		delete backOp.xAxis;
		delete backOp.yAxis;
		
		var tmpObj={};
		var tmpSeries={
            name: '数据',
            type: 'pie',
            radius : '80%',
            center: ['50%', '50%'],
			label:{
				normal:{
					show:true,
					formatter:function(data){
						return data.name+' - '+data.percent+'%\r\n'+(data.value/60/60).toFixed(1)+'H';
					}
				}
			},
            data:[],
        }
		for(i in data){
			var dd=data[i][0];
			delete dd.id;
			for(n in dd){
				if(n in tmpObj){
					tmpObj[n]+=dd[n];
				}else{
					tmpObj[n]=dd[n];
				}
			}
		}
		var tmpType={
			value:'',
			name:'',
			itemStyle:{
				normal:{
					color:'',
				}
			},
		};
		for(i in tmpObj){
			if(i=='power'){
				tmpType.name='关机';
				tmpType.value=ts-tmpObj[i];
				tmpType.itemStyle.normal.color='#999999';
			}else if(i=='run'){
				tmpType.name='空转';
				tmpType.value=tmpObj[i]-tmpObj.task;
				tmpType.itemStyle.normal.color=opArr.run.color;
				
				backOp.legend.data.unshift(tmpType.name);
				tmpSeries.data.unshift(clone(tmpType));
				continue;
			}else{
				tmpType.name=opArr[i].name;
				tmpType.value=tmpObj[i];
				tmpType.itemStyle.normal.color=i=='task'?'#1B7C1B':opArr[i].color;
			}
			backOp.legend.data.push(tmpType.name);
			tmpSeries.data.push(clone(tmpType));
		}
		backOp.series.push(tmpSeries);
		//console.log(backOp);
		return backOp;
	}
	
}