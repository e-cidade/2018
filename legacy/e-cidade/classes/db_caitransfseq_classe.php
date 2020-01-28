<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

//MODULO: caixa
//CLASSE DA ENTIDADE caitransfseq
class cl_caitransfseq { 
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
   var $k94_seqtransf = 0; 
   var $k94_transf = 0; 
   var $k94_anousu = 0; 
   var $k94_data_dia = null; 
   var $k94_data_mes = null; 
   var $k94_data_ano = null; 
   var $k94_data = null; 
   var $k94_valor = 0; 
   var $k94_finalidade = null; 
   var $k94_id_usuario = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k94_seqtransf = int8 = Codigo da Transferência 
                 k94_transf = int4 = Trasferencia 
                 k94_anousu = int4 = Exercício 
                 k94_data = date = Data 
                 k94_valor = float8 = Valor 
                 k94_finalidade = text = Finalidade 
                 k94_id_usuario = int4 = Usuário 
                 ";
   //funcao construtor da classe 
   function cl_caitransfseq() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("caitransfseq"); 
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
       $this->k94_seqtransf = ($this->k94_seqtransf == ""?@$GLOBALS["HTTP_POST_VARS"]["k94_seqtransf"]:$this->k94_seqtransf);
       $this->k94_transf = ($this->k94_transf == ""?@$GLOBALS["HTTP_POST_VARS"]["k94_transf"]:$this->k94_transf);
       $this->k94_anousu = ($this->k94_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["k94_anousu"]:$this->k94_anousu);
       if($this->k94_data == ""){
         $this->k94_data_dia = ($this->k94_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k94_data_dia"]:$this->k94_data_dia);
         $this->k94_data_mes = ($this->k94_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k94_data_mes"]:$this->k94_data_mes);
         $this->k94_data_ano = ($this->k94_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k94_data_ano"]:$this->k94_data_ano);
         if($this->k94_data_dia != ""){
            $this->k94_data = $this->k94_data_ano."-".$this->k94_data_mes."-".$this->k94_data_dia;
         }
       }
       $this->k94_valor = ($this->k94_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["k94_valor"]:$this->k94_valor);
       $this->k94_finalidade = ($this->k94_finalidade == ""?@$GLOBALS["HTTP_POST_VARS"]["k94_finalidade"]:$this->k94_finalidade);
       $this->k94_id_usuario = ($this->k94_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["k94_id_usuario"]:$this->k94_id_usuario);
     }else{
       $this->k94_seqtransf = ($this->k94_seqtransf == ""?@$GLOBALS["HTTP_POST_VARS"]["k94_seqtransf"]:$this->k94_seqtransf);
     }
   }
   // funcao para inclusao
   function incluir ($k94_seqtransf){ 
      $this->atualizacampos();
     if($this->k94_transf == null ){ 
       $this->erro_sql = " Campo Trasferencia nao Informado.";
       $this->erro_campo = "k94_transf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k94_anousu == null ){ 
       $this->erro_sql = " Campo Exercício nao Informado.";
       $this->erro_campo = "k94_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k94_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "k94_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k94_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "k94_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k94_finalidade == null ){ 
       $this->erro_sql = " Campo Finalidade nao Informado.";
       $this->erro_campo = "k94_finalidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k94_id_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "k94_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k94_seqtransf == "" || $k94_seqtransf == null ){
       $result = db_query("select nextval('caitransfseq_k94_seqtransf_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: caitransfseq_k94_seqtransf_seq do campo: k94_seqtransf"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k94_seqtransf = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from caitransfseq_k94_seqtransf_seq");
       if(($result != false) && (pg_result($result,0,0) < $k94_seqtransf)){
         $this->erro_sql = " Campo k94_seqtransf maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k94_seqtransf = $k94_seqtransf; 
       }
     }
     if(($this->k94_seqtransf == null) || ($this->k94_seqtransf == "") ){ 
       $this->erro_sql = " Campo k94_seqtransf nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into caitransfseq(
                                       k94_seqtransf 
                                      ,k94_transf 
                                      ,k94_anousu 
                                      ,k94_data 
                                      ,k94_valor 
                                      ,k94_finalidade 
                                      ,k94_id_usuario 
                       )
                values (
                                $this->k94_seqtransf 
                               ,$this->k94_transf 
                               ,$this->k94_anousu 
                               ,".($this->k94_data == "null" || $this->k94_data == ""?"null":"'".$this->k94_data."'")." 
                               ,$this->k94_valor 
                               ,'$this->k94_finalidade' 
                               ,$this->k94_id_usuario 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->k94_seqtransf) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->k94_seqtransf) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k94_seqtransf;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k94_seqtransf));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8780,'$this->k94_seqtransf','I')");
       $resac = db_query("insert into db_acount values($acount,1500,8780,'','".AddSlashes(pg_result($resaco,0,'k94_seqtransf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1500,8781,'','".AddSlashes(pg_result($resaco,0,'k94_transf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1500,8784,'','".AddSlashes(pg_result($resaco,0,'k94_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1500,8785,'','".AddSlashes(pg_result($resaco,0,'k94_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1500,8783,'','".AddSlashes(pg_result($resaco,0,'k94_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1500,8782,'','".AddSlashes(pg_result($resaco,0,'k94_finalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1500,8786,'','".AddSlashes(pg_result($resaco,0,'k94_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k94_seqtransf=null) { 
      $this->atualizacampos();
     $sql = " update caitransfseq set ";
     $virgula = "";
     if(trim($this->k94_seqtransf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k94_seqtransf"])){ 
       $sql  .= $virgula." k94_seqtransf = $this->k94_seqtransf ";
       $virgula = ",";
       if(trim($this->k94_seqtransf) == null ){ 
         $this->erro_sql = " Campo Codigo da Transferência nao Informado.";
         $this->erro_campo = "k94_seqtransf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k94_transf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k94_transf"])){ 
       $sql  .= $virgula." k94_transf = $this->k94_transf ";
       $virgula = ",";
       if(trim($this->k94_transf) == null ){ 
         $this->erro_sql = " Campo Trasferencia nao Informado.";
         $this->erro_campo = "k94_transf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k94_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k94_anousu"])){ 
       $sql  .= $virgula." k94_anousu = $this->k94_anousu ";
       $virgula = ",";
       if(trim($this->k94_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "k94_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k94_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k94_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k94_data_dia"] !="") ){ 
       $sql  .= $virgula." k94_data = '$this->k94_data' ";
       $virgula = ",";
       if(trim($this->k94_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "k94_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k94_data_dia"])){ 
         $sql  .= $virgula." k94_data = null ";
         $virgula = ",";
         if(trim($this->k94_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "k94_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k94_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k94_valor"])){ 
       $sql  .= $virgula." k94_valor = $this->k94_valor ";
       $virgula = ",";
       if(trim($this->k94_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "k94_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k94_finalidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k94_finalidade"])){ 
       $sql  .= $virgula." k94_finalidade = '$this->k94_finalidade' ";
       $virgula = ",";
       if(trim($this->k94_finalidade) == null ){ 
         $this->erro_sql = " Campo Finalidade nao Informado.";
         $this->erro_campo = "k94_finalidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k94_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k94_id_usuario"])){ 
       $sql  .= $virgula." k94_id_usuario = $this->k94_id_usuario ";
       $virgula = ",";
       if(trim($this->k94_id_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "k94_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k94_seqtransf!=null){
       $sql .= " k94_seqtransf = $this->k94_seqtransf";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k94_seqtransf));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8780,'$this->k94_seqtransf','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k94_seqtransf"]))
           $resac = db_query("insert into db_acount values($acount,1500,8780,'".AddSlashes(pg_result($resaco,$conresaco,'k94_seqtransf'))."','$this->k94_seqtransf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k94_transf"]))
           $resac = db_query("insert into db_acount values($acount,1500,8781,'".AddSlashes(pg_result($resaco,$conresaco,'k94_transf'))."','$this->k94_transf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k94_anousu"]))
           $resac = db_query("insert into db_acount values($acount,1500,8784,'".AddSlashes(pg_result($resaco,$conresaco,'k94_anousu'))."','$this->k94_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k94_data"]))
           $resac = db_query("insert into db_acount values($acount,1500,8785,'".AddSlashes(pg_result($resaco,$conresaco,'k94_data'))."','$this->k94_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k94_valor"]))
           $resac = db_query("insert into db_acount values($acount,1500,8783,'".AddSlashes(pg_result($resaco,$conresaco,'k94_valor'))."','$this->k94_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k94_finalidade"]))
           $resac = db_query("insert into db_acount values($acount,1500,8782,'".AddSlashes(pg_result($resaco,$conresaco,'k94_finalidade'))."','$this->k94_finalidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k94_id_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1500,8786,'".AddSlashes(pg_result($resaco,$conresaco,'k94_id_usuario'))."','$this->k94_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k94_seqtransf;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k94_seqtransf;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k94_seqtransf;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k94_seqtransf=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k94_seqtransf));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8780,'$k94_seqtransf','E')");
         $resac = db_query("insert into db_acount values($acount,1500,8780,'','".AddSlashes(pg_result($resaco,$iresaco,'k94_seqtransf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1500,8781,'','".AddSlashes(pg_result($resaco,$iresaco,'k94_transf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1500,8784,'','".AddSlashes(pg_result($resaco,$iresaco,'k94_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1500,8785,'','".AddSlashes(pg_result($resaco,$iresaco,'k94_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1500,8783,'','".AddSlashes(pg_result($resaco,$iresaco,'k94_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1500,8782,'','".AddSlashes(pg_result($resaco,$iresaco,'k94_finalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1500,8786,'','".AddSlashes(pg_result($resaco,$iresaco,'k94_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from caitransfseq
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k94_seqtransf != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k94_seqtransf = $k94_seqtransf ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k94_seqtransf;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k94_seqtransf;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k94_seqtransf;
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
        $this->erro_sql   = "Record Vazio na Tabela:caitransfseq";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $k94_seqtransf=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from caitransfseq ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = caitransfseq.k94_id_usuario";
     $sql .= "      inner join caitransf  on  caitransf.k91_transf = caitransfseq.k94_transf";     
     $sql .= "      inner join db_config c1 on  c1.codigo = caitransf.k91_instit";
     $sql2 = "";
     if($dbwhere==""){
       if($k94_seqtransf!=null ){
         $sql2 .= " where caitransfseq.k94_seqtransf = $k94_seqtransf "; 
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
   function sql_query_efetuadas ( $k94_seqtransf=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from caitransfseq ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = caitransfseq.k94_id_usuario";
     $sql .= "      inner join caitransf  on  caitransf.k91_transf = caitransfseq.k94_transf";     
     $sql .= "      inner join db_config c1 on  c1.codigo = caitransf.k91_instit";
     
     $sql .= "      inner join caitransfdest  on  caitransfdest.k92_transf = caitransf.k91_transf";     
     $sql .= "      inner join db_config c2 on  c2.codigo = caitransfdest.k92_instit";

     $sql2 = "";
     if($dbwhere==""){
       if($k94_seqtransf!=null ){
         $sql2 .= " where caitransfseq.k94_seqtransf = $k94_seqtransf "; 
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
   function sql_query_file ( $k94_seqtransf=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from caitransfseq ";
     $sql2 = "";
     if($dbwhere==""){
       if($k94_seqtransf!=null ){
         $sql2 .= " where caitransfseq.k94_seqtransf = $k94_seqtransf "; 
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
   function sql_query_notif ( $k94_seqtransf=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from caitransfseq ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = caitransfseq.k94_id_usuario";
     $sql .= "      inner join caitransf  on  caitransf.k91_transf = caitransfseq.k94_transf";     
     $sql .= "      inner join db_config c1 on  c1.codigo = caitransf.k91_instit";
     
     $sql .= "      inner join caitransfdest  on  caitransfdest.k92_transf = caitransf.k91_transf";     
     $sql .= "      inner join db_config c2 on  c2.codigo = caitransfdest.k92_instit";

     $sql2 = "";
     if($dbwhere==""){
       if($k94_seqtransf!=null ){
         $sql2 .= " where caitransfseq.k94_seqtransf = $k94_seqtransf "; 
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