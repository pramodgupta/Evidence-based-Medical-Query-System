
import javax.xml.stream.XMLStreamConstants;//XMLInputFactory;
import java.io.*;
import java.util.Arrays;
import java.util.Iterator;

import org.apache.hadoop.conf.Configuration;
import org.apache.hadoop.fs.Path;
import org.apache.hadoop.fs.FSDataInputStream;
import org.apache.hadoop.fs.FSDataOutputStream;
import org.apache.hadoop.fs.FileSystem;
import org.apache.hadoop.io.DataOutputBuffer;
import org.apache.hadoop.io.IntWritable;
import org.apache.hadoop.io.LongWritable;
import org.apache.hadoop.io.Text;
import org.apache.hadoop.mapreduce.Job;
import org.apache.hadoop.mapreduce.Mapper;
import org.apache.hadoop.mapreduce.Reducer;
import org.apache.hadoop.mapreduce.InputSplit;
import org.apache.hadoop.mapreduce.RecordReader;
import org.apache.hadoop.mapreduce.TaskAttemptContext;
import org.apache.hadoop.mapreduce.TaskAttemptID;
import org.apache.hadoop.mapreduce.lib.input.FileSplit;
import org.apache.hadoop.mapreduce.lib.input.FileInputFormat;
import org.apache.hadoop.mapreduce.lib.input.TextInputFormat;
import org.apache.hadoop.mapreduce.lib.output.FileOutputFormat;
import org.apache.hadoop.mapreduce.lib.output.TextOutputFormat;
import javax.xml.stream.*;

public class xmlworker
{

        public static class XmlInputFormat1 extends TextInputFormat {

        public static final String START_TAG_KEY = "xmlinput.start";
        public static final String END_TAG_KEY = "xmlinput.end";


        public RecordReader<LongWritable, Text> createRecordReader(
                InputSplit split, TaskAttemptContext context) {
            return new XmlRecordReader();
        }

        /**
         * XMLRecordReader class to read through a given xml document to output
         * xml blocks as records as specified by the start tag and end tag
         *
         */
       
        public static class XmlRecordReader extends
                RecordReader<LongWritable, Text> {
            private byte[] startTag;
            private byte[] endTag;
            private long start;
            private long end;
            private FSDataInputStream fsin;
            private DataOutputBuffer buffer = new DataOutputBuffer();

            private LongWritable key = new LongWritable();
            private Text value = new Text();
                @Override
            public void initialize(InputSplit split, TaskAttemptContext context)
                    throws IOException, InterruptedException {
                Configuration conf = context.getConfiguration();
                startTag = conf.get(START_TAG_KEY).getBytes("utf-8");
                endTag = conf.get(END_TAG_KEY).getBytes("utf-8");
                FileSplit fileSplit = (FileSplit) split;

                // open the file and seek to the start of the split
                start = fileSplit.getStart();
                end = start + fileSplit.getLength();
                Path file = fileSplit.getPath();
                FileSystem fs = file.getFileSystem(conf);
                fsin = fs.open(fileSplit.getPath());
                fsin.seek(start);

            }
        @Override
            public boolean nextKeyValue() throws IOException,
                    InterruptedException {
                if (fsin.getPos() < end) {
                    if (readUntilMatch(startTag, false)) {
                        try {
                            buffer.write(startTag);
                            if (readUntilMatch(endTag, true)) {
                                key.set(fsin.getPos());
                                value.set(buffer.getData(), 0,
                                        buffer.getLength());
                                return true;
                            }
                        } finally {
                            buffer.reset();
                        }
                    }
                }
                return false;
            }
        @Override
           public LongWritable getCurrentKey() throws IOException,
                    InterruptedException {
                return key;
            }

        @Override
            public Text getCurrentValue() throws IOException,
                    InterruptedException {
                return value;
            }
        @Override
            public void close() throws IOException {
                fsin.close();
            }
        @Override
            public float getProgress() throws IOException {
                return (fsin.getPos() - start) / (float) (end - start);
            }

            private boolean readUntilMatch(byte[] match, boolean withinBlock)
                    throws IOException {
                int i = 0;
                while (true) {
                    int b = fsin.read();
                    // end of file:
                    if (b == -1)
                        return false;
                    // save to buffer:
                    if (withinBlock)
                        buffer.write(b);
                    // check if we're matching:
                    if (b == match[i]) {
                        i++;
                        if (i >= match.length)
                            return true;
                    } else
                        i = 0;
                    // see if we've passed the stop point:
                    if (!withinBlock && i == 0 && fsin.getPos() >= end)
                        return false;
                }
            }
        }
    }


        public static class Map extends Mapper<LongWritable, Text,
    Text, Text> {
  @Override
  protected void map(LongWritable key, Text value,
                     Mapper.Context context)
      throws
      IOException, InterruptedException {
    String document = value.toString();
    System.out.println("‘" + document + "‘");
        try {
      XMLStreamReader reader =
          XMLInputFactory.newInstance().createXMLStreamReader(new
              ByteArrayInputStream(document.getBytes()));
      String propertyName = "";
      String propertyValue = "";
      String currentElement = "";
      String rank_value = "--";
      String xxxx="";
      int flag=0;
      String ele[] = {
              "condition", "intervention_type","intervention_name","enrollment","gender",
          };
      String flagitems[] = {
    		  
    		  "description","arm_group_label","criteria","textblock","minimum_age","maximum_age","healthy_volunteers"      };
      String yesflag[]={
    		  "arm_group_label","healthy_volunteers"
    		  
      };
          int counter = 0;
      
      
      while (reader.hasNext()) {
        int code = reader.next();
      
        switch (code) {
          case XMLStreamConstants.START_ELEMENT: //START_ELEMENT:
            currentElement = reader.getLocalName();
            
            if(currentElement.equalsIgnoreCase("intervention") || currentElement.equalsIgnoreCase("eligibility"))
      	  {
      		flag =1;
      		 
      			 
      		        		        			 
      	  }     
            
            if(currentElement.equalsIgnoreCase("property"))
      	  {
      		  rank_value = reader.getAttributeValue(0);
      		  
      	  }
           
            
            counter++;
            break;
          case XMLStreamConstants.CHARACTERS:  //CHARACTERS:
        	  propertyName = currentElement;
        	          	       	  
        	  if(Arrays.asList(ele).contains(propertyName) || Arrays.asList(flagitems).contains(propertyName))
              {
        		  if(Arrays.asList(ele).contains(propertyName))
        		  {
	                  propertyValue = reader.getText();
	                  if(!propertyValue.trim().equals(""))
	                  {
	                      xxxx = (new StringBuilder(String.valueOf(propertyName.trim()))).append("$$$").append(propertyValue.trim()).toString();
	                      String coun = Integer.toString(counter);
	                      context.write(new Text(rank_value.trim()), new Text(xxxx.trim()));
	                  }
        		  }
        		  
        		  if(Arrays.asList(flagitems).contains(propertyName) && flag ==1)
        		  {
	                  propertyValue = reader.getText();
	                  if(!propertyValue.trim().equals(""))
	                  {
	                      xxxx = (new StringBuilder(String.valueOf(propertyName.trim()))).append("$$$").append(propertyValue.trim()).toString();
	                      String coun = Integer.toString(counter);
	                      context.write(new Text(rank_value.trim()), new Text(xxxx.trim()));
	                  }
        		  }
        		  if(Arrays.asList(yesflag).contains(propertyName))
        				  {flag =0 ;}
        		 
              }
        	  
        	  
           
            break;
        }
      }
      reader.close();

     
    }
        catch(Exception e){
                throw new IOException(e);

                }

  }
}
public static class Reduce
    extends Reducer<Text, Text, Text, Text> {

  @Override
  protected void setup(
      Context context)
      throws IOException, InterruptedException {
    context.write(new Text("<configuration>"), null);
  }

  @Override
  protected void cleanup(
      Context context)
      throws IOException, InterruptedException {
    context.write(new Text("</configuration>"), null);
  }

  private Text outputKey = new Text();
  public void reduce(Text key, Iterable values, Context context)
          throws IOException, InterruptedException
      {
          String keyprevious = "";
          context.write(new Text("<property>"), null);
          for(Iterator iterator = values.iterator(); iterator.hasNext();)
          {
              Text value = (Text)iterator.next();
              outputKey.set(constructPropertyXml(key, value));
              String valu1 = value.toString();
              if(valu1.contains("$$$"))
              {
                  context.write(outputKey, null);
              }
              keyprevious = key.toString();
          }

          context.write(new Text("</property>"), null);
      }

  public static String constructPropertyXml(Text name, Text value) {
	  StringBuilder sb = new StringBuilder();
      String valu = value.toString();
      if(valu.contains("$$$"))
      {
          int getpos = valu.indexOf("$$$");
          String named = valu.substring(0, getpos);
          String valued = valu.substring(getpos + 3);
          sb.append("<").append(named).append(">").append(valued).append("</").append(named).append(">");
      }
      return sb.toString();
  }
}



        public static void main(String[] args) throws Exception
        {
                Configuration conf = new Configuration();
                FileSystem fs = FileSystem.get(conf);

                conf.set("xmlinput.start", "<?xml");
                conf.set("xmlinput.end", "</clinical_study>");
                Job job = new Job(conf);
                job.setJarByClass(xmlworker.class);
                job.setOutputKeyClass(Text.class);
                job.setOutputValueClass(Text.class);

                job.setMapperClass(xmlworker.Map.class);
                job.setReducerClass(xmlworker.Reduce.class);

                job.setInputFormatClass(XmlInputFormat1.class);
                job.setOutputFormatClass(TextOutputFormat.class);
                
                Path outputfile = new Path("pramodgupta/xmlout1");
                try{
                	if(fs.exists(outputfile))
                	{
                		fs.delete(outputfile,true);
                		
                	}
                	
                }catch(Exception e)
                {
                	
                }
                

                FileInputFormat.addInputPath(job, new Path("pramodgupta/xmlinput1"));
                FileOutputFormat.setOutputPath(job, outputfile);

                job.waitForCompletion(true);
        }
}