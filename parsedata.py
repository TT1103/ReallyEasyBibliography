import sys, json,subprocess,time

try:
	data = json.loads(sys.argv[1])
except:
	print "Error loading data"
	sys.exit(1)
result = []
cnt=0
idx=1

idlist={}

maximum = len(data)
def getDate():
	t = time.strftime("%c").split()
	return t[2]+" "+t[1]+". "+t[4]


def parseBib(s):
	if len(s)==0:
		return ""

	book=False
	if s.startswith("@book"):
		title=""
		author=""
		dateacc=""
		datepub="n.d"
		publisher="n.p."
		book=True
	else:
		title=""
		author=""
		dateacc=getDate()
		datepub="n.d."
		publisher="n.p."

	s=s.split("\n")
	
	for x in s: #for each line
		if x.find("title") != -1:
			for i in range(x.find("title") + 7, len(x)):
				if (x[i] == '}'):
					break
				title += x[i]
		if x.find("author") != -1:
			for i in range(x.find("author") + 8, len(x)):
				if (x[i] == '}'):
					break
				author += x[i]
		if x.find("year") != -1:
			datepub=""
			for i in range(x.find("year") + 6, len(x)):
				if (x[i] == '}'):
					break
				datepub += x[i]
		if x.find("publisher") != -1:
			publisher=""
			for i in range(x.find("publisher") + 11, len(x)):
				if (x[i] == '}'):
					break
				publisher += x[i]

	if book:
		combined =author+". <i>" +title+".</i> "+publisher+", "+datepub+". "
	else:
		combined =author+". \""+title+".\" "+publisher+", "+datepub+". Web. "+dateacc+"."
		
	return combined

for entry in data:
	if not entry:
		continue
	s= entry.strip().split()
 
	a=""
	urlfound=False
	titlefound=False
	titlename =""
	sourceId=""
	for x in range(len(s)):
		cur = s[x]
		if cur.startswith("Title"):
			if titlefound: #start anew
				if urlfound:
					result.append(a)
					cnt+=1
					p=subprocess.check_output(["python", "/var/www/html/scholar.py", "-c","1","-C",sourceId,"--citation","bt"])
					#(output, err)=p.communicate()
					result.append("<p>"+parseBib(p)+"</p>")
					a=""
					urlfound=False
				else:
					a=""
			t=x+1
			cur=s[t]
			while cur not in ["URL","Year","Citations"]:
				a+=cur+" "
				t+=1
				cur=s[t]
			a+="\n"
			titlename=a
			a="<h3>"+a+"</h3>"
			titlefound=True
		elif cur.startswith("URL"):
			a+= "<p><a href="+s[x+1]+">" + s[x+1] + "</a></p>\n"
			urlfound=True
		elif cur.startswith("ID"):
			sourceId = s[x+1]


		if cnt == 5 * maximum:
			break
	

	
if len(result)==0:
	print "No results found."
else:
	print "\n".join(result)
