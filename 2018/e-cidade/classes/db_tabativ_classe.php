<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: issqn
//CLASSE DA ENTIDADE tabativ
class cl_tabativ { 
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
   var $q07_inscr = 0; 
   var $q07_seq = 0; 
   var $q07_ativ = 0; 
   var $q07_datain_dia = null; 
   var $q07_datain_mes = null; 
   var $q07_datain_ano = null; 
   var $q07_datain = null; 
   var $q07_datafi_dia = null; 
   var $q07_datafi_mes = null; 
   var $q07_datafi_ano = null; 
   var $q07_datafi = null; 
   var $q07_databx_dia = null; 
   var $q07_databx_mes = null; 
   var $q07_databx_ano = null; 
   var $q07_databx = null; 
   var $q07_quant = 0; 
   var $q07_tipbx = null; 
   var $q07_perman = 'f'; 
   var $q07_horaini = null; 
   var $q07_horafim = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q07_inscr = int4 = inscricao 
                 q07_seq = int4 = sequencia 
                 q07_ativ = int4 = codigo da atividade 
                 q07_datain = date = Início 
                 q07_datafi = date = Fim 
                 q07_databx = date = Baixa 
                 q07_quant = int4 = quantidade 
                 q07_tipbx = varchar(1) = tipo de baixa 
                 q07_perman = bool = Permanente ou provisório 
                 q07_horaini = char(5) = Hora Inicial 
                 q07_horafim = char(5) = Hora Final 
                 ";
   //funcao construtor da classe 
   function cl_tabativ() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tabativ"); 
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
       $this->q07_inscr = ($this->q07_inscr == ""?@$GLOBALS["HTTP_POST_VARS"]["q07_inscr"]:$this->q07_inscr);
       $this->q07_seq = ($this->q07_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["q07_seq"]:$this->q07_seq);
       $this->q07_ativ = ($this->q07_ativ == ""?@$GLOBALS["HTTP_POST_VARS"]["q07_ativ"]:$this->q07_ativ);
       if($this->q07_datain == ""){
         $this->q07_datain_dia = ($this->q07_datain_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q07_datain_dia"]:$this->q07_datain_dia);
         $this->q07_datain_mes = ($this->q07_datain_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q07_datain_mes"]:$this->q07_datain_mes);
         $this->q07_datain_ano = ($this->q07_datain_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q07_datain_ano"]:$this->q07_datain_ano);
         if($this->q07_datain_dia != ""){
            $this->q07_datain = $this->q07_datain_ano."-".$this->q07_datain_mes."-".$this->q07_datain_dia;
         }
       }
       if($this->q07_datafi == ""){
         $this->q07_datafi_dia = ($this->q07_datafi_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q07_datafi_dia"]:$this->q07_datafi_dia);
         $this->q07_datafi_mes = ($this->q07_datafi_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q07_datafi_mes"]:$this->q07_datafi_mes);
         $this->q07_datafi_ano = ($this->q07_datafi_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q07_datafi_ano"]:$this->q07_datafi_ano);
         if($this->q07_datafi_dia != ""){
            $this->q07_datafi = $this->q07_datafi_ano."-".$this->q07_datafi_mes."-".$this->q07_datafi_dia;
         }
       }
       if($this->q07_databx == ""){
         $this->q07_databx_dia = ($this->q07_databx_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q07_databx_dia"]:$this->q07_databx_dia);
         $this->q07_databx_mes = ($this->q07_databx_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q07_databx_mes"]:$this->q07_databx_mes);
         $this->q07_databx_ano = ($this->q07_databx_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q07_databx_ano"]:$this->q07_databx_ano);
         if($this->q07_databx_dia != ""){
            $this->q07_databx = $this->q07_databx_ano."-".$this->q07_databx_mes."-".$this->q07_databx_dia;
         }
       }
       $this->q07_quant = ($this->q07_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["q07_quant"]:$this->q07_quant);
       $this->q07_tipbx = ($this->q07_tipbx == ""?@$GLOBALS["HTTP_POST_VARS"]["q07_tipbx"]:$this->q07_tipbx);
       $this->q07_perman = ($this->q07_perman == "f"?@$GLOBALS["HTTP_POST_VARS"]["q07_perman"]:$this->q07_perman);
       $this->q07_horaini = ($this->q07_horaini == ""?@$GLOBALS["HTTP_POST_VARS"]["q07_horaini"]:$this->q07_horaini);
       $this->q07_horafim = ($this->q07_horafim == ""?@$GLOBALS["HTTP_POST_VARS"]["q07_horafim"]:$this->q07_horafim);
     }else{
       $this->q07_inscr = ($this->q07_inscr == ""?@$GLOBALS["HTTP_POST_VARS"]["q07_inscr"]:$this->q07_inscr);
       $this->q07_seq = ($this->q07_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["q07_seq"]:$this->q07_seq);
     }
   }
   // funcao para inclusao
   function incluir ($q07_inscr,$q07_seq){ 
      $this->atualizacampos();
     if($this->q07_ativ == null ){ 
       $this->erro_sql = " Campo codigo da atividade nao Informado.";
       $this->erro_campo = "q07_ativ";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q07_datain == null ){ 
       $this->q07_datain = "null";
     }
     if($this->q07_datafi == null ){ 
       $this->q07_datafi = "null";
     }
     if($this->q07_databx == null ){ 
       $this->q07_databx = "null";
     }
     if($this->q07_quant == null ){ 
       $this->erro_sql = " Campo quantidade nao Informado.";
       $this->erro_campo = "q07_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q07_tipbx == null ){ 
       $this->erro_sql = " Campo tipo de baixa nao Informado.";
       $this->erro_campo = "q07_tipbx";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q07_perman == null ){ 
       $this->erro_sql = " Campo Permanente ou provisório nao Informado.";
       $this->erro_campo = "q07_perman";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->q07_inscr = $q07_inscr; 
       $this->q07_seq = $q07_seq; 
     if(($this->q07_inscr == null) || ($this->q07_inscr == "") ){ 
       $this->erro_sql = " Campo q07_inscr nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->q07_seq == null) || ($this->q07_seq == "") ){ 
       $this->erro_sql = " Campo q07_seq nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tabativ(
                                       q07_inscr 
                                      ,q07_seq 
                                      ,q07_ativ 
                                      ,q07_datain 
                                      ,q07_datafi 
                                      ,q07_databx 
                                      ,q07_quant 
                                      ,q07_tipbx 
                                      ,q07_perman 
                                      ,q07_horaini 
                                      ,q07_horafim 
                       )
                values (
                                $this->q07_inscr 
                               ,$this->q07_seq 
                               ,$this->q07_ativ 
                               ,".($this->q07_datain == "null" || $this->q07_datain == ""?"null":"'".$this->q07_datain."'")." 
                               ,".($this->q07_datafi == "null" || $this->q07_datafi == ""?"null":"'".$this->q07_datafi."'")." 
                               ,".($this->q07_databx == "null" || $this->q07_databx == ""?"null":"'".$this->q07_databx."'")." 
                               ,$this->q07_quant 
                               ,'$this->q07_tipbx' 
                               ,'$this->q07_perman' 
                               ,'$this->q07_horaini' 
                               ,'$this->q07_horafim' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->q07_inscr."-".$this->q07_seq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->q07_inscr."-".$this->q07_seq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q07_inscr."-".$this->q07_seq;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q07_inscr,$this->q07_seq));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,291,'$this->q07_inscr','I')");
       $resac = db_query("insert into db_acountkey values($acount,292,'$this->q07_seq','I')");
       $resac = db_query("insert into db_acount values($acount,67,291,'','".AddSlashes(pg_result($resaco,0,'q07_inscr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,67,292,'','".AddSlashes(pg_result($resaco,0,'q07_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,67,293,'','".AddSlashes(pg_result($resaco,0,'q07_ativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,67,294,'','".AddSlashes(pg_result($resaco,0,'q07_datain'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,67,295,'','".AddSlashes(pg_result($resaco,0,'q07_datafi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,67,296,'','".AddSlashes(pg_result($resaco,0,'q07_databx'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,67,297,'','".AddSlashes(pg_result($resaco,0,'q07_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,67,724,'','".AddSlashes(pg_result($resaco,0,'q07_tipbx'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,67,4793,'','".AddSlashes(pg_result($resaco,0,'q07_perman'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,67,12647,'','".AddSlashes(pg_result($resaco,0,'q07_horaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,67,12648,'','".AddSlashes(pg_result($resaco,0,'q07_horafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q07_inscr=null,$q07_seq=null) { 
      $this->atualizacampos();
     $sql = " update tabativ set ";
     $virgula = "";
     if(trim($this->q07_inscr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q07_inscr"])){ 
       $sql  .= $virgula." q07_inscr = $this->q07_inscr ";
       $virgula = ",";
       if(trim($this->q07_inscr) == null ){ 
         $this->erro_sql = " Campo inscricao nao Informado.";
         $this->erro_campo = "q07_inscr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q07_seq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q07_seq"])){ 
       $sql  .= $virgula." q07_seq = $this->q07_seq ";
       $virgula = ",";
       if(trim($this->q07_seq) == null ){ 
         $this->erro_sql = " Campo sequencia nao Informado.";
         $this->erro_campo = "q07_seq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q07_ativ)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q07_ativ"])){ 
       $sql  .= $virgula." q07_ativ = $this->q07_ativ ";
       $virgula = ",";
       if(trim($this->q07_ativ) == null ){ 
         $this->erro_sql = " Campo codigo da atividade nao Informado.";
         $this->erro_campo = "q07_ativ";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q07_datain)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q07_datain_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q07_datain_dia"] !="") ){ 
       $sql  .= $virgula." q07_datain = '$this->q07_datain' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q07_datain_dia"])){ 
         $sql  .= $virgula." q07_datain = null ";
         $virgula = ",";
       }
     }
     if(trim($this->q07_datafi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q07_datafi_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q07_datafi_dia"] !="") ){ 
       $sql  .= $virgula." q07_datafi = '$this->q07_datafi' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q07_datafi_dia"])){ 
         $sql  .= $virgula." q07_datafi = null ";
         $virgula = ",";
       }
     }
     if(trim($this->q07_databx)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q07_databx_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q07_databx_dia"] !="") ){ 
       $sql  .= $virgula." q07_databx = '$this->q07_databx' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q07_databx_dia"])){ 
         $sql  .= $virgula." q07_databx = null ";
         $virgula = ",";
       }
     }
     if(trim($this->q07_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q07_quant"])){ 
       $sql  .= $virgula." q07_quant = $this->q07_quant ";
       $virgula = ",";
       if(trim($this->q07_quant) == null ){ 
         $this->erro_sql = " Campo quantidade nao Informado.";
         $this->erro_campo = "q07_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q07_tipbx)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q07_tipbx"])){ 
       $sql  .= $virgula." q07_tipbx = '$this->q07_tipbx' ";
       $virgula = ",";
       if(trim($this->q07_tipbx) == null ){ 
         $this->erro_sql = " Campo tipo de baixa nao Informado.";
         $this->erro_campo = "q07_tipbx";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q07_perman)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q07_perman"])){ 
       $sql  .= $virgula." q07_perman = '$this->q07_perman' ";
       $virgula = ",";
       if(trim($this->q07_perman) == null ){ 
         $this->erro_sql = " Campo Permanente ou provisório nao Informado.";
         $this->erro_campo = "q07_perman";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q07_horaini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q07_horaini"])){ 
       $sql  .= $virgula." q07_horaini = '$this->q07_horaini' ";
       $virgula = ",";
     }
     if(trim($this->q07_horafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q07_horafim"])){ 
       $sql  .= $virgula." q07_horafim = '$this->q07_horafim' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($q07_inscr!=null){
       $sql .= " q07_inscr = $this->q07_inscr";
     }
     if($q07_seq!=null){
       $sql .= " and  q07_seq = $this->q07_seq";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q07_inscr,$this->q07_seq));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,291,'$this->q07_inscr','A')");
         $resac = db_query("insert into db_acountkey values($acount,292,'$this->q07_seq','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q07_inscr"]))
           $resac = db_query("insert into db_acount values($acount,67,291,'".AddSlashes(pg_result($resaco,$conresaco,'q07_inscr'))."','$this->q07_inscr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q07_seq"]))
           $resac = db_query("insert into db_acount values($acount,67,292,'".AddSlashes(pg_result($resaco,$conresaco,'q07_seq'))."','$this->q07_seq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q07_ativ"]))
           $resac = db_query("insert into db_acount values($acount,67,293,'".AddSlashes(pg_result($resaco,$conresaco,'q07_ativ'))."','$this->q07_ativ',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q07_datain"]))
           $resac = db_query("insert into db_acount values($acount,67,294,'".AddSlashes(pg_result($resaco,$conresaco,'q07_datain'))."','$this->q07_datain',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q07_datafi"]))
           $resac = db_query("insert into db_acount values($acount,67,295,'".AddSlashes(pg_result($resaco,$conresaco,'q07_datafi'))."','$this->q07_datafi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q07_databx"]))
           $resac = db_query("insert into db_acount values($acount,67,296,'".AddSlashes(pg_result($resaco,$conresaco,'q07_databx'))."','$this->q07_databx',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q07_quant"]))
           $resac = db_query("insert into db_acount values($acount,67,297,'".AddSlashes(pg_result($resaco,$conresaco,'q07_quant'))."','$this->q07_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q07_tipbx"]))
           $resac = db_query("insert into db_acount values($acount,67,724,'".AddSlashes(pg_result($resaco,$conresaco,'q07_tipbx'))."','$this->q07_tipbx',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q07_perman"]))
           $resac = db_query("insert into db_acount values($acount,67,4793,'".AddSlashes(pg_result($resaco,$conresaco,'q07_perman'))."','$this->q07_perman',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q07_horaini"]))
           $resac = db_query("insert into db_acount values($acount,67,12647,'".AddSlashes(pg_result($resaco,$conresaco,'q07_horaini'))."','$this->q07_horaini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q07_horafim"]))
           $resac = db_query("insert into db_acount values($acount,67,12648,'".AddSlashes(pg_result($resaco,$conresaco,'q07_horafim'))."','$this->q07_horafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q07_inscr."-".$this->q07_seq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q07_inscr."-".$this->q07_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q07_inscr."-".$this->q07_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q07_inscr=null,$q07_seq=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q07_inscr,$q07_seq));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,291,'$q07_inscr','E')");
         $resac = db_query("insert into db_acountkey values($acount,292,'$q07_seq','E')");
         $resac = db_query("insert into db_acount values($acount,67,291,'','".AddSlashes(pg_result($resaco,$iresaco,'q07_inscr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,67,292,'','".AddSlashes(pg_result($resaco,$iresaco,'q07_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,67,293,'','".AddSlashes(pg_result($resaco,$iresaco,'q07_ativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,67,294,'','".AddSlashes(pg_result($resaco,$iresaco,'q07_datain'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,67,295,'','".AddSlashes(pg_result($resaco,$iresaco,'q07_datafi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,67,296,'','".AddSlashes(pg_result($resaco,$iresaco,'q07_databx'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,67,297,'','".AddSlashes(pg_result($resaco,$iresaco,'q07_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,67,724,'','".AddSlashes(pg_result($resaco,$iresaco,'q07_tipbx'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,67,4793,'','".AddSlashes(pg_result($resaco,$iresaco,'q07_perman'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,67,12647,'','".AddSlashes(pg_result($resaco,$iresaco,'q07_horaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,67,12648,'','".AddSlashes(pg_result($resaco,$iresaco,'q07_horafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tabativ
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q07_inscr != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q07_inscr = $q07_inscr ";
        }
        if($q07_seq != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q07_seq = $q07_seq ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q07_inscr."-".$q07_seq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q07_inscr."-".$q07_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q07_inscr."-".$q07_seq;
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
        $this->erro_sql   = "Record Vazio na Tabela:tabativ";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $q07_inscr=null,$q07_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tabativ ";
     $sql .= "      inner join issbase  on  issbase.q02_inscr = tabativ.q07_inscr";
     $sql .= "      inner join ativid  on  ativid.q03_ativ = tabativ.q07_ativ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = issbase.q02_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($q07_inscr!=null ){
         $sql2 .= " where tabativ.q07_inscr = $q07_inscr "; 
       } 
       if($q07_seq!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " tabativ.q07_seq = $q07_seq "; 
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
   // funcao do sql 
   function sql_query_file ( $q07_inscr=null,$q07_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tabativ ";
     $sql2 = "";
     if($dbwhere==""){
       if($q07_inscr!=null ){
         $sql2 .= " where tabativ.q07_inscr = $q07_inscr "; 
       } 
       if($q07_seq!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " tabativ.q07_seq = $q07_seq "; 
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
   function sql_query_atividade_inscr ( $q07_inscr=null,$campos="*",$ordem=null,$dbwhere=""){
     
     $sql = "select ";
     
     if($campos != "*" ){
       
       $campos_sql = split("#",$campos);
       $virgula = "";
       
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
       
     } else {
       $sql.="      distinct                                                                 ";
       $sql.="      q07_inscr ,                                                              ";
       $sql.="      q07_seq   ,                                                              ";
       $sql.="      q07_ativ  ,                                                              ";
       $sql.="      q03_ativ  ,                                                              ";
       $sql.="      q03_descr ,                                                              ";
       $sql.="      q07_datain,                                                              ";
       $sql.="      q07_datafi,                                                              ";
       $sql.="      q07_databx,                                                              ";
       $sql.="      case when q07_perman='f'                                                 ";
       $sql.="           then 'Provisorio' ::char(10)                                        ";
       $sql.="           else 'Permanente'                                                   ";
       $sql.="      end as q07_perman,                                                       ";
       $sql.="      q07_quant ,                                                              ";
       $sql.="      q81_descr ,                                                              ";
       $sql.="      case when q88_seq is null                                                ";
       $sql.="           then ' '::char(1)                                                   ";
       $sql.="           else '*'::char(1)                                                   ";
       $sql.="      end as q88_inscr,                                                        ";
       $sql.="      case when q11_tipcalc is null                                            ";
       $sql.="           then 0 else q11_tipcalc                                             ";
       $sql.="      end as q11_tipcalc                                                       ";
     }
     $sql.=" from tabativ                                                                 ";
     $sql.="      left outer join ativprinc       on q88_inscr       = q07_inscr          ";
     $sql.="                                     and q88_seq         = q07_seq            ";
     $sql.="      left outer join tabativtipcalc  on q07_seq         = q11_seq            ";
     $sql.="                                     and q11_inscr       = q07_inscr          ";
     $sql.="      inner      join ativid          on ativid.q03_ativ = tabativ.q07_ativ   ";
     $sql.="      left outer join tipcalc         on q11_tipcalc     = q81_codigo         ";

     $sql2= "";
     
     if ($dbwhere=="") {
       
       if($q07_inscr!=null ){
         $sql2 .= " where q07_inscr = $q07_inscr ";
       }
       
     } else if ($dbwhere != "") {
       $sql2 = " where $dbwhere";
     }
     
     $sql .= $sql2;
     
     if ($ordem != null ) {
       
       $sql        .= " order by ";
       $campos_sql  = split("#",$ordem);
       $virgula     = "";
       
       for ( $i=0; $i < sizeof($campos_sql); $i++ ) { 
         
         $sql    .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_queryinf ( $q07_inscr=null,$q07_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tabativ ";
     $sql .= "      inner join issbase  on  issbase.q02_inscr = tabativ.q07_inscr";
     $sql .= "      left join issruas  on  issruas.q02_inscr = tabativ.q07_inscr";
     $sql .= "      left join issbairro  on  issbairro.q13_inscr = tabativ.q07_inscr";
     $sql .= "      left join ruas  on  ruas.j14_codigo = issruas.j14_codigo";
     $sql .= "      left join bairro  on  bairro.j13_codi = issbairro.q13_bairro";
     
     $sql .= "      inner join ativid  on  ativid.q03_ativ = tabativ.q07_ativ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = issbase.q02_numcgm";
     $sql .= "      left outer join tabativbaixa on tabativ.q07_inscr = tabativbaixa.q11_inscr and tabativ.q07_seq = tabativbaixa.q11_seq";
     $sql .= "      left outer join ativprinc on tabativ.q07_inscr =  ativprinc.q88_inscr and tabativ.q07_seq = ativprinc.q88_seq";
     $sql2 = "";
     if($dbwhere==""){
       if($q07_inscr!=null ){
         $sql2 .= " where tabativ.q07_inscr = $q07_inscr "; 
       } 
       if($q07_seq!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " tabativ.q07_seq = $q07_seq "; 
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
   function sql_query_princ ( $q07_inscr=null,$q07_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tabativ ";
     $sql .= "      inner join issbase  on  issbase.q02_inscr = tabativ.q07_inscr";
     $sql .= "      inner join ativid  on  ativid.q03_ativ = tabativ.q07_ativ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = issbase.q02_numcgm";
     $sql .= "      inner join ativprinc  on  ativprinc.q88_inscr = tabativ.q07_inscr and ativprinc.q88_seq = tabativ.q07_seq";
     $sql .= "      left outer join tabativbaixa on tabativ.q07_inscr = tabativbaixa.q11_inscr and tabativ.q07_seq = tabativbaixa.q11_seq";
     $sql2 = "";
     if($dbwhere==""){
       if($q07_inscr!=null ){
         $sql2 .= " where tabativ.q07_inscr = $q07_inscr "; 
       } 
       if($q07_seq!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " tabativ.q07_seq = $q07_seq "; 
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