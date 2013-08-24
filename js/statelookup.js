function State(){
	this.code;
	this.name;
	this.coords['lat'];
	this.corrds['lng'];
	this.idealzooom;
}

var statelookup = new Object();
var statecenter = new Object();

statecenter.AK = "61.3850,-152.2683";
statelookup['AK'] = {
	"code" : "AK",
	"name" : "Alaska",
	"coords" : {
		"lat" : 61.3850,
		"lng" : -152.2683
	},
	"idealzoom" : 7
}

statecenter.AL = "32.7990,-86.8073";
statelookup['AL'] = {
	"code" : "AL",
	"name" : "Alabama",
	"coords" : {
		"lat" : 32.7990,
		"lng" : -86.8073
	},
	"idealzoom" : 7
}

statecenter.AR = "34.9513,-92.3809";
statelookup['AR'] = {
	"code" : "AR",
	"name" : "Arkansas",
	"coords" : {
		"lat" : 34.9513,
		"lng" : -92.3809
	},
	"idealzoom" : 7
}

statecenter.AZ = "33.7712,-111.3877";
statelookup['AZ'] = {
	"code" : "AZ",
	"name" : "Arizona",
	"coords" : {
		"lat" : 33.7712,
		"lng" : -111.3877
	},
	"idealzoom" : 7
}

statecenter.CA = "36.1700,-119.7462";
statelookup['CA'] = {
	"code" : "CA",
	"name" : "California",
	"coords" : {
		"lat" : 36.1700,
		"lng" : -119.7462
	},
	"idealzoom" : 6
}

statecenter.CO = "39.0646,-105.3272";
statelookup['CO'] = {
	"code" : "CO",
	"name" : "Colorado",
	"coords" : {
		"lat" : 39.0646,
		"lng" :-105.3272
	},
	"idealzoom" : 7
}


statecenter.CT = "41.5834,-72.7622";
statelookup['CT'] = {
	"code" : "CT",
	"name" : "Connecticut",
	"coords" : {
		"lat" : 41.5834,
		"lng" : -72.7622
	},
	"idealzoom" : 9
}

statecenter.DC = "38.8964,-77.0262";
statelookup['DC'] = {
	"code" : "DC",
	"name" : "District of Columbia",
	"coords" : {
		"lat" : 38.8964,
		"lng" : -77.0262
	},
	"idealzoom" : 7
}

statecenter.DE = "39.3498,-75.5148";
statelookup['DE'] = {
	"code" : "DE",
	"name" : "Delaware",
	"coords" : {
		"lat" : 39.3498,
		"lng" : -75.5148
	},
	"idealzoom" : 9
}

statecenter.FL = "27.8333,-81.7170";
statelookup['FL'] = {
	"code" : "FL",
	"name" : "Florida",
	"coords" : {
		"lat" : 27.8333,
		"lng" : -81.7170
	},
	"idealzoom" : 7
}

statecenter.GA = "32.9866,-83.6487";
statelookup['GA'] = {
	"code" : "GA",
	"name" : "Georgia",
	"coords" : {
		"lat" : 32.9866,
		"lng" : -83.6487
	},
	"idealzoom" : 7
}

statecenter.HI = "21.1098,-157.5311";
statelookup['HI'] = {
	"code" : "HI",
	"name" : "Hawaii",
	"coords" : {
		"lat" : 21.1098,
		"lng" : -157.5311
	},
	"idealzoom" : 7
}

statecenter.IA = "42.0046,-93.2140";
statelookup['IA'] = {
	"code" : "IA",
	"name" : "Iowa",
	"coords" : {
		"lat" : 42.0046,
		"lng" : -93.2140
	},
	"idealzoom" : 7
}

statecenter.ID = "44.2394,-114.5103";
statelookup['ID'] = {
	"code" : "ID",
	"name" : "Idaho",
	"coords" : {
		"lat" : 44.2394,
		"lng" : -114.5103
	},
	"idealzoom" : 6
}

statecenter.IL = "40.3363,-89.0022";
statelookup['IL'] = {
	"code" : "IL",
	"name" : "Illinois",
	"coords" : {
		"lat" : 40.3363,
		"lng" : -89.0022
	},
	"idealzoom" : 7
}

statecenter.IN = "39.8647,-86.2604";
statelookup['IN'] = {
	"code" : "IN",
	"name" : "Indiana",
	"coords" : {
		"lat" : 39.8647,
		"lng" : -86.2604
	},
	"idealzoom" : 7
}

statecenter.KS = "38.5111,-96.8005";
statelookup['KS'] = {
	"code" : "KS",
	"name" : "Kansas",
	"coords" : {
		"lat" : 38.5111,
		"lng" : -96.8005
	},
	"idealzoom" : 7
}

statecenter.KY = "";
statelookup['KY'] = {
	"code" : "KY",
	"name" : "Kentucky",
	"coords" : {
		"lat" : 37.6690,
		"lng" : -84.6514
	},
	"idealzoom" : 7
}

statecenter.LA = "31.1801,-91.8749";
statelookup['LA'] = {
	"code" : "LA",
	"name" : "Louisiana",
	"coords" : {
		"lat" : 31.1801,
		"lng" : -91.8749
	},
	"idealzoom" : 7
}

statecenter.MA = "42.2373,-71.5314";
statelookup['MA'] = {
	"code" : "MA",
	"name" : "Massachusetts",
	"coords" : {
		"lat" : 42.2373,
		"lng" : -71.5314
	},
	"idealzoom" : 8
}

statecenter.MD = "39.0724,-76.7902";
statelookup['MD'] = {
	"code" : "MD",
	"name" : "Maryland",
	"coords" : {
		"lat" : 39.0724,
		"lng" : -76.7902
	},
	"idealzoom" : 7
}

statecenter.ME = "44.6074,-69.3977";
statelookup['ME'] = {
	"code" : "ME",
	"name" : "Maine",
	"coords" : {
		"lat" : 44.6074,
		"lng" : -69.3977
	},
	"idealzoom" : 7
}

statecenter.MI = "43.3504,-84.5603";
statelookup['MI'] = {
	"code" : "MI",
	"name" : "Michigan",
	"coords" : {
		"lat" : 43.3504,
		"lng" : -84.5603
	},
	"idealzoom" : 7
}

statecenter.MN = "45.7326,-93.9196";
statelookup['MN'] = {
	"code" : "MN",
	"name" : "Minnesota",
	"coords" : {
		"lat" : 45.7326,
		"lng" : -93.9196
	},
	"idealzoom" : 7
}

statecenter.MO = "38.4623,-92.3020";
statelookup['MO'] = {
	"code" : "MO",
	"name" : "Missouri",
	"coords" : {
		"lat" : 38.4623,
		"lng" : -92.3020
	},
	"idealzoom" : 7
}

statecenter.MS = "32.7673,-89.6812";
statelookup['MS'] = {
	"code" : "MS",
	"name" : "Mississippi",
	"coords" : {
		"lat" : 32.7673,
		"lng" : -89.6812
	},
	"idealzoom" : 7
}

statecenter.MT = "46.9048,-110.3261";
statelookup['MT'] = {
	"code" : "MT",
	"name" : "Montana",
	"coords" : {
		"lat" : 46.9048,
		"lng" : -110.3261
	},
	"idealzoom" : 6
}

statecenter.NC = "35.6411,-79.8431";
statelookup['NC'] = {
	"code" : "NC",
	"name" : "North Carolina",
	"coords" : {
		"lat" : 35.6411,
		"lng" : -79.8431
	},
	"idealzoom" : 7
}

statecenter.ND = "47.5362,-99.7930";
statelookup['ND'] = {
	"code" : "ND",
	"name" : "North Dakota",
	"coords" : {
		"lat" : 47.5362,
		"lng" : -99.7930
	},
	"idealzoom" : 7
}

statecenter.NE = "41.1289,-98.2883";
statelookup['NE'] = {
	"code" : "NE",
	"name" : "Nebraska",
	"coords" : {
		"lat" : 41.1289,
		"lng" : -98.2883
	},
	"idealzoom" : 7
}

statecenter.NH = "43.4108,-71.5653";
statelookup['NH'] = {
	"code" : "NH",
	"name" : "New Hampshire",
	"coords" : {
		"lat" : 43.4108,
		"lng" : -71.5653
	},
	"idealzoom" : 8
}

statecenter.NJ = "40.3140,-74.5089";
statelookup['NJ'] = {
	"code" : "NJ",
	"name" : "New Jersey",
	"coords" : {
		"lat" : 40.3140,
		"lng" : -74.5089
	},
	"idealzoom" : 8
}

statecenter.NM = "34.8375,-106.2371";
statelookup['NM'] = {
	"code" : "NM",
	"name" : "New Mexico",
	"coords" : {
		"lat" : 34.8375,
		"lng" : -106.2371
	},
	"idealzoom" : 7
}

statecenter.NV = "38.4199,-117.1219";
statelookup['NV'] = {
	"code" : "NV",
	"name" : "Nevada",
	"coords" : {
		"lat" : 38.4199,
		"lng" : -117.1219
	},
	"idealzoom" : 7
}

statecenter.NY = "42.1497,-74.9384";
statelookup['NY'] = {
	"code" : "NY",
	"name" : "New York",
	"coords" : {
		"lat" : 42.1497,
		"lng" : -74.9384
	},
	"idealzoom" : 7
}

statecenter.OH = "40.3736,-82.7755";
statelookup['OH'] = {
	"code" : "OH",
	"name" : "Ohio",
	"coords" : {
		"lat" : 40.3736,
		"lng" : -82.7755
	},
	"idealzoom" : 7
}

statecenter.OK = "35.5376,-96.9247";
statelookup['OK'] = {
	"code" : "OK",
	"name" : "Oklahoma",
	"coords" : {
		"lat" : 35.5376,
		"lng" : -96.9247
	},
	"idealzoom" : 7
}

statecenter.OR = "44.5672,-122.1269";
statelookup['OR'] = {
	"code" : "OR",
	"name" : "Oregon",
	"coords" : {
		"lat" :44.5672,
		"lng" : -122.1269
	},
	"idealzoom" : 7
}

statecenter.PA = "40.5773,-77.2640";
statelookup['PA'] = {
	"code" : "PA",
	"name" : "Pennsylvania",
	"coords" : {
		"lat" : 40.5773,
		"lng" : -77.2640
	},
	"idealzoom" : 7
}

statecenter.RI = "41.6772,-71.5101";
statelookup['RI'] = {
	"code" : "RI",
	"name" : "Rhode Island",
	"coords" : {
		"lat" : 41.6772,
		"lng" : -71.5101
	},
	"idealzoom" : 9
}

statecenter.SC = "33.8191,-80.9066";
statelookup['SC'] = {
	"code" : "SC",
	"name" : "South Carolina",
	"coords" : {
		"lat" : 33.8191,
		"lng" : -80.9066
	},
	"idealzoom" : 7
}

statecenter.SD = "44.2853,-99.4632";
statelookup['SD'] = {
	"code" : "SD",
	"name" : "South Dakota",
	"coords" : {
		"lat" : 44.2853,
		"lng" : -99.4632
	},
	"idealzoom" : 7
}

statecenter.TN = "35.7449,-86.7489";
statelookup['TN'] = {
	"code" : "TN",
	"name" : "Tennessee",
	"coords" : {
		"lat" : 35.7449,
		"lng" : -86.7489
	},
	"idealzoom" : 7
}

statecenter.TX = "31.1060,-97.6475";
statelookup['TX'] = {
	"code" : "TX",
	"name" : "Texas",
	"coords" : {
		"lat" : 31.1060,
		"lng" : -97.6475
	},
	"idealzoom" : 6
}

statecenter.UT = "40.1135,-111.8535";
statelookup['UT'] = {
	"code" : "UT",
	"name" : "Utah",
	"coords" : {
		"lat" : 40.1135,
		"lng" : -111.8535
	},
	"idealzoom" : 7
}

statecenter.VA = "37.7680,-78.2057";
statelookup['VA'] = {
	"code" : "VA",
	"name" : "Virginia",
	"coords" : {
		"lat" : 37.7680,
		"lng" : -78.2057
	},
	"idealzoom" : 7
}

statecenter.VT = "44.0407,-72.7093";
statelookup['VT'] = {
	"code" : "VT",
	"name" : "Vermont",
	"coords" : {
		"lat" : 44.0407,
		"lng" : -72.7093
	},
	"idealzoom" : 7
}

statecenter.WA = "47.3917,-121.5708";
statelookup['WA'] = {
	"code" : "WA",
	"name" : "Washington",
	"coords" : {
		"lat" : 47.3917,
		"lng" : -121.5708
	},
	"idealzoom" : 7
}

statecenter.WI = "44.2563,-89.6385";
statelookup['WI'] = {
	"code" : "WI",
	"name" : "Wisconsin",
	"coords" : {
		"lat" : 44.2563,
		"lng" : -89.6385
	},
	"idealzoom" : 7
}

statecenter.WV = "38.4680,-80.9696";
statelookup['WV'] = {
	"code" : "WV",
	"name" : "West Virginia",
	"coords" : {
		"lat" : 38.4680,
		"lng" : -80.9696
	},
	"idealzoom" : 7
}

statecenter.WY = "42.7475,-107.2085";
statelookup['WY'] = {
	"code" : "WY",
	"name" : "Wyoming",
	"coords" : {
		"lat" : 42.7475,
		"lng" : -107.2085
	},
	"idealzoom" : 7
}

statecenter.PR = "18.2766,-66.3350";
statelookup['PR'] = {
	"code" : "PR",
	"name" : "Puerto Rico",
	"coords" : {
		"lat" : 18.2766,
		"lng" : -66.3350
	},
	"idealzoom" : 7
}
statecenter.AS = "14.2417,-170.7197";
statelookup['AS'] = {
	"code" : "AS",
	"name" : "American Samoa",
	"coords" : {
		"lat" :14.2417,
		"lng" :-170.7197
	},
	"idealzoom" : 7
}


statecenter.GU = "14.2631, 144.4635";
statelookup['GU'] = {
		"code" : "GU",
		"name" : "Guam",
		"coords" : {
			"lat" : 14.2631, 
			"lng" : 144.4635
		},
		"idealzoom" : 7
	}


statecenter.MP = "14.8058,145.5505";
statelookup['MP'] = {
		"code" : "MP",
		"name" : "Northern Mariana Islands",
		"coords" : {
			"lat" : 14.8058,
			"lng" : 145.5505
		},
		"idealzoom" : 7
	}



statecenter.VI = "18.0001,-64.8199";
statelookup['VI'] = {
		"code" : "VI",
		"name" : "Virgin Islands",
		"coords" : {
			"lat" : 18.0001,
			"lng" : -64.8199
		},
		"idealzoom" : 7
	}
statecenter.MB = "55.18,97.0";
statelookup['MB'] = {
		"code" : "MB",
		"name" : "Manitoba",
		"coords" : {
			"lat" : 55.18,
			"lng" : 97.0
		},
		"idealzoom" : 7
	}


/*
AB 	Alberta 	Alberta 	First letter of first two syllables
BC 	British Columbia 	Colombie-Britannique 	Initials
MB 	Manitoba 	Manitoba 	First letter of first and last syllables
NB 	New Brunswick 	Nouveau-Brunswick 	Initials
NL 	Newfoundland and Labrador 	Terre-Neuve-et-Labrador 	Initials
NS 	Nova Scotia 	Nouvelle-�cosse 	Initials
NT 	Northwest Territories 	Territoires du Nord-Ouest 	Initials
NU 	Nunavut 	Nunavut 	First two letters
ON 	Ontario 	Ontario 	First two letters
PE 	Prince Edward Island 	�le-du-Prince-�douard 	Initials of first two words
QC 	Quebec 	Qu�bec 	First and last letters
SK 	Saskatchewan 	Saskatchewan 	First letter of first two syllables
YT[A] 	Yukon 	Yukon 	Initials of "Yukon Territory"*/