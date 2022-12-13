<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//MODULO: contrib
//CLASSE DA ENTIDADE edital
class cl_edital { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $d01_codedi = 0; 
   var $d01_numero = null; 
   var $d01_descr = null; 
   var $d01_idlog = 0; 
   var $d01_data_dia = null; 
   var $d01_data_mes = null; 
   var $d01_data_ano = null; 
   var $d01_data = null; 
   var $d01_perc = 0; 
   var $d01_receit = 0; 
   var $d01_numtot = 0; 
   var $d01_privenc_dia = null; 
   var $d01_privenc_mes = null; 
   var $d01_privenc_ano = null; 
   var $d01_privenc = null; 
   var $d01_perunica = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 d01_codedi = int4 = Codigo Edital 
                 d01_numero = varchar(20) = Número do Edital 
                 d01_descr = text = Descricao do Edital 
                 d01_idlog = int4 = Login 
                 d01_data = date = Data Edital 
                 d01_perc = float8 = Percentual 
                 d01_receit = int4 = Receita 
                 d01_numtot = int4 = Total de parcelas 
                 d01_privenc = date = Primeiro vencimento 
                 d01_perunica = int4 = Percentual desconto única 
                 ";
   //funcao construtor da classe 
   function cl_edital() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("edital"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->d01_codedi = ($this->d01_codedi == ""?@$GLOBALS["HTTP_POST_VARS"]["d01_codedi"]:$this->d01_codedi);
       $this->d01_numero = ($this->d01_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["d01_numero"]:$this->d01_numero);
       $this->d01_descr = ($this->d01_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["d01_descr"]:$this->d01_descr);
       $this->d01_idlog = ($this->d01_idlog == ""?@$GLOBALS["HTTP_POST_VARS"]["d01_idlog"]:$this->d01_idlog);
       if($this->d01_data == ""){
         $this->d01_data_dia = ($this->d01_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["d01_data_dia"]:$this->d01_data_dia);
         $this->d01_data_mes = ($this->d01_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["d01_data_mes"]:$this->d01_data_mes);
         $this->d01_data_ano = ($this->d01_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["d01_data_ano"]:$this->d01_data_ano);
         if($this->d01_data_dia != ""){
            $this->d01_data = $this->d01_data_ano."-".$this->d01_data_mes."-".$this->d01_data_dia;
         }
       }
       $this->d01_perc = ($this->d01_perc == ""?@$GLOBALS["HTTP_POST_VARS"]["d01_perc"]:$this->d01_perc);
       $this->d01_receit = ($this->d01_receit == ""?@$GLOBALS["HTTP_POST_VARS"]["d01_receit"]:$this->d01_receit);
       $this->d01_numtot = ($this->d01_numtot == ""?@$GLOBALS["HTTP_POST_VARS"]["d01_numtot"]:$this->d01_numtot);
       if($this->d01_privenc == ""){
         $this->d01_privenc_dia = ($this->d01_privenc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["d01_privenc_dia"]:$this->d01_privenc_dia);
         $this->d01_privenc_mes = ($this->d01_privenc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["d01_privenc_mes"]:$this->d01_privenc_mes);
         $this->d01_privenc_ano = ($this->d01_privenc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["d01_privenc_ano"]:$this->d01_privenc_ano);
         if($this->d01_privenc_dia != ""){
            $this->d01_privenc = $this->d01_privenc_ano."-".$this->d01_privenc_mes."-".$this->d01_privenc_dia;
         }
       }
       $this->d01_perunica = ($this->d01_perunica == ""?@$GLOBALS["HTTP_POST_VARS"]["d01_perunica"]:$this->d01_perunica);
     }else{
       $this->d01_codedi = ($this->d01_codedi == ""?@$GLOBALS["HTTP_POST_VARS"]["d01_codedi"]:$this->d01_codedi);
     }
   }
   // funcao para inclusao
   function incluir ($d01_codedi){ 
      $this->atualizacampos();
     if($this->d01_numero == null ){ 
       $this->erro_sql = " Campo Número do Edital nao Informado.";
       $this->erro_campo = "d01_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d01_descr == null ){ 
       $this->erro_sql = " Campo Descricao do Edital nao Informado.";
       $this->erro_campo = "d01_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d01_idlog == null ){ 
       $this->erro_sql = " Campo Login nao Informado.";
       $this->erro_campo = "d01_idlog";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d01_data == null ){ 
       $this->erro_sql = " Campo Data Edital nao Informado.";
       $this->erro_campo = "d01_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d01_perc == null ){ 
       $this->d01_perc = "0";
     }
     if($this->d01_receit == null ){ 
       $this->erro_sql = " Campo Receita nao Informado.";
       $this->erro_campo = "d01_receit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d01_numtot == null ){ 
       $this->erro_sql = " Campo Total de parcelas nao Informado.";
       $this->erro_campo = "d01_numtot";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d01_privenc == null ){ 
       $this->erro_sql = " Campo Primeiro vencimento nao Informado.";
       $this->erro_campo = "d01_privenc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d01_perunica == null ){ 
       $this->d01_perunica = "0";
     }
     if($d01_codedi == "" || $d01_codedi == null ){
       $result = db_query("select nextval('edital_d01_codedi_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: edital_d01_codedi_seq do campo: d01_codedi"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->d01_codedi = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from edital_d01_codedi_seq");
       if(($result != false) && (pg_result($result,0,0) < $d01_codedi)){
         $this->erro_sql = " Campo d01_codedi maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->d01_codedi = $d01_codedi; 
       }
     }
     if(($this->d01_codedi == null) || ($this->d01_codedi == "") ){ 
       $this->erro_sql = " Campo d01_codedi nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into edital(
                                       d01_codedi 
                                      ,d01_numero 
                                      ,d01_descr 
                                      ,d01_idlog 
                                      ,d01_data 
                                      ,d01_perc 
                                      ,d01_receit 
                                      ,d01_numtot 
                                      ,d01_privenc 
                                      ,d01_perunica 
                       )
                values (
                                $this->d01_codedi 
                               ,'$this->d01_numero' 
                               ,'$this->d01_descr' 
                               ,$this->d01_idlog 
                               ,".($this->d01_data == "null" || $this->d01_data == ""?"null":"'".$this->d01_data."'")." 
                               ,$this->d01_perc 
                               ,$this->d01_receit 
                               ,$this->d01_numtot 
                               ,".($this->d01_privenc == "null" || $this->d01_privenc == ""?"null":"'".$this->d01_privenc."'")." 
                               ,$this->d01_perunica 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->d01_codedi) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->d01_codedi) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->d01_codedi;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->d01_codedi));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,677,'$this->d01_codedi','I')");
       $resac = db_query("insert into db_acount values($acount,126,677,'','".AddSlashes(pg_result($resaco,0,'d01_codedi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,126,678,'','".AddSlashes(pg_result($resaco,0,'d01_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,126,679,'','".AddSlashes(pg_result($resaco,0,'d01_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,126,680,'','".AddSlashes(pg_result($resaco,0,'d01_idlog'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,126,681,'','".AddSlashes(pg_result($resaco,0,'d01_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,126,682,'','".AddSlashes(pg_result($resaco,0,'d01_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,126,683,'','".AddSlashes(pg_result($resaco,0,'d01_receit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,126,4774,'','".AddSlashes(pg_result($resaco,0,'d01_numtot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,126,4773,'','".AddSlashes(pg_result($resaco,0,'d01_privenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,126,4775,'','".AddSlashes(pg_result($resaco,0,'d01_perunica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($d01_codedi=null) { 
      $this->atualizacampos();
     $sql = " update edital set ";
     $virgula = "";
     if(trim($this->d01_codedi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d01_codedi"])){ 
       $sql  .= $virgula." d01_codedi = $this->d01_codedi ";
       $virgula = ",";
       if(trim($this->d01_codedi) == null ){ 
         $this->erro_sql = " Campo Codigo Edital nao Informado.";
         $this->erro_campo = "d01_codedi";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d01_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d01_numero"])){ 
       $sql  .= $virgula." d01_numero = '$this->d01_numero' ";
       $virgula = ",";
       if(trim($this->d01_numero) == null ){ 
         $this->erro_sql = " Campo Número do Edital nao Informado.";
         $this->erro_campo = "d01_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d01_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d01_descr"])){ 
       $sql  .= $virgula." d01_descr = '$this->d01_descr' ";
       $virgula = ",";
       if(trim($this->d01_descr) == null ){ 
         $this->erro_sql = " Campo Descricao do Edital nao Informado.";
         $this->erro_campo = "d01_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d01_idlog)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d01_idlog"])){ 
       $sql  .= $virgula." d01_idlog = $this->d01_idlog ";
       $virgula = ",";
       if(trim($this->d01_idlog) == null ){ 
         $this->erro_sql = " Campo Login nao Informado.";
         $this->erro_campo = "d01_idlog";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d01_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d01_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["d01_data_dia"] !="") ){ 
       $sql  .= $virgula." d01_data = '$this->d01_data' ";
       $virgula = ",";
       if(trim($this->d01_data) == null ){ 
         $this->erro_sql = " Campo Data Edital nao Informado.";
         $this->erro_campo = "d01_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["d01_data_dia"])){ 
         $sql  .= $virgula." d01_data = null ";
         $virgula = ",";
         if(trim($this->d01_data) == null ){ 
           $this->erro_sql = " Campo Data Edital nao Informado.";
           $this->erro_campo = "d01_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->d01_perc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d01_perc"])){ 
        if(trim($this->d01_perc)=="" && isset($GLOBALS["HTTP_POST_VARS"]["d01_perc"])){ 
           $this->d01_perc = "0" ; 
        } 
       $sql  .= $virgula." d01_perc = $this->d01_perc ";
       $virgula = ",";
     }
     if(trim($this->d01_receit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d01_receit"])){ 
       $sql  .= $virgula." d01_receit = $this->d01_receit ";
       $virgula = ",";
       if(trim($this->d01_receit) == null ){ 
         $this->erro_sql = " Campo Receita nao Informado.";
         $this->erro_campo = "d01_receit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d01_numtot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d01_numtot"])){ 
       $sql  .= $virgula." d01_numtot = $this->d01_numtot ";
       $virgula = ",";
       if(trim($this->d01_numtot) == null ){ 
         $this->erro_sql = " Campo Total de parcelas nao Informado.";
         $this->erro_campo = "d01_numtot";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d01_privenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d01_privenc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["d01_privenc_dia"] !="") ){ 
       $sql  .= $virgula." d01_privenc = '$this->d01_privenc' ";
       $virgula = ",";
       if(trim($this->d01_privenc) == null ){ 
         $this->erro_sql = " Campo Primeiro vencimento nao Informado.";
         $this->erro_campo = "d01_privenc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["d01_privenc_dia"])){ 
         $sql  .= $virgula." d01_privenc = null ";
         $virgula = ",";
         if(trim($this->d01_privenc) == null ){ 
           $this->erro_sql = " Campo Primeiro vencimento nao Informado.";
           $this->erro_campo = "d01_privenc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->d01_perunica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d01_perunica"])){ 
        if(trim($this->d01_perunica)=="" && isset($GLOBALS["HTTP_POST_VARS"]["d01_perunica"])){ 
           $this->d01_perunica = "0" ; 
        } 
       $sql  .= $virgula." d01_perunica = $this->d01_perunica ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($d01_codedi!=null){
       $sql .= " d01_codedi = $this->d01_codedi";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->d01_codedi));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,677,'$this->d01_codedi','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d01_codedi"]))
           $resac = db_query("insert into db_acount values($acount,126,677,'".AddSlashes(pg_result($resaco,$conresaco,'d01_codedi'))."','$this->d01_codedi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d01_numero"]))
           $resac = db_query("insert into db_acount values($acount,126,678,'".AddSlashes(pg_result($resaco,$conresaco,'d01_numero'))."','$this->d01_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d01_descr"]))
           $resac = db_query("insert into db_acount values($acount,126,679,'".AddSlashes(pg_result($resaco,$conresaco,'d01_descr'))."','$this->d01_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d01_idlog"]))
           $resac = db_query("insert into db_acount values($acount,126,680,'".AddSlashes(pg_result($resaco,$conresaco,'d01_idlog'))."','$this->d01_idlog',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d01_data"]))
           $resac = db_query("insert into db_acount values($acount,126,681,'".AddSlashes(pg_result($resaco,$conresaco,'d01_data'))."','$this->d01_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d01_perc"]))
           $resac = db_query("insert into db_acount values($acount,126,682,'".AddSlashes(pg_result($resaco,$conresaco,'d01_perc'))."','$this->d01_perc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d01_receit"]))
           $resac = db_query("insert into db_acount values($acount,126,683,'".AddSlashes(pg_result($resaco,$conresaco,'d01_receit'))."','$this->d01_receit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d01_numtot"]))
           $resac = db_query("insert into db_acount values($acount,126,4774,'".AddSlashes(pg_result($resaco,$conresaco,'d01_numtot'))."','$this->d01_numtot',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d01_privenc"]))
           $resac = db_query("insert into db_acount values($acount,126,4773,'".AddSlashes(pg_result($resaco,$conresaco,'d01_privenc'))."','$this->d01_privenc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d01_perunica"]))
           $resac = db_query("insert into db_acount values($acount,126,4775,'".AddSlashes(pg_result($resaco,$conresaco,'d01_perunica'))."','$this->d01_perunica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->d01_codedi;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->d01_codedi;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->d01_codedi;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($d01_codedi=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($d01_codedi));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,677,'$d01_codedi','E')");
         $resac = db_query("insert into db_acount values($acount,126,677,'','".AddSlashes(pg_result($resaco,$iresaco,'d01_codedi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,126,678,'','".AddSlashes(pg_result($resaco,$iresaco,'d01_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,126,679,'','".AddSlashes(pg_result($resaco,$iresaco,'d01_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,126,680,'','".AddSlashes(pg_result($resaco,$iresaco,'d01_idlog'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,126,681,'','".AddSlashes(pg_result($resaco,$iresaco,'d01_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,126,682,'','".AddSlashes(pg_result($resaco,$iresaco,'d01_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,126,683,'','".AddSlashes(pg_result($resaco,$iresaco,'d01_receit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,126,4774,'','".AddSlashes(pg_result($resaco,$iresaco,'d01_numtot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,126,4773,'','".AddSlashes(pg_result($resaco,$iresaco,'d01_privenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,126,4775,'','".AddSlashes(pg_result($resaco,$iresaco,'d01_perunica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from edital
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($d01_codedi != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " d01_codedi = $d01_codedi ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$d01_codedi;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$d01_codedi;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$d01_codedi;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:edital";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $d01_codedi=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from edital ";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = edital.d01_receit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = edital.d01_idlog";
     $sql .= "      inner join tabrecjm  on  tabrecjm.k02_codjm = tabrec.k02_codjm";
     $sql2 = "";
     if($dbwhere==""){
       if($d01_codedi!=null ){
         $sql2 .= " where edital.d01_codedi = $d01_codedi "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_file ( $d01_codedi=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from edital ";
     $sql2 = "";
     if($dbwhere==""){
       if($d01_codedi!=null ){
         $sql2 .= " where edital.d01_codedi = $d01_codedi "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_servicos ( $d01_codedi=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from edital ";
     $sql .= "      inner join editalrua    on d01_codedi             = d02_codedi        ";
     $sql .= "      inner join editalserv   on d04_contri             = d02_contri        ";
     $sql .= "      inner join tabrec       on tabrec.k02_codigo      = edital.d01_receit ";
     $sql .= "      inner join db_usuarios  on db_usuarios.id_usuario = edital.d01_idlog  ";
     $sql .= "      inner join tabrecjm     on tabrecjm.k02_codjm     = tabrec.k02_codjm  ";
     $sql2 = "";
     if($dbwhere==""){
       if($d01_codedi!=null ){
         $sql2 .= " where edital.d01_codedi = $d01_codedi "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
?>