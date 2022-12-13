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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_layouttxtgeracao
class cl_db_layouttxtgeracao { 
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
   var $db55_sequencial = 0; 
   var $db55_layouttxt = 0; 
   var $db55_seqlayout = 0; 
   var $db55_data_dia = null; 
   var $db55_data_mes = null; 
   var $db55_data_ano = null; 
   var $db55_data = null; 
   var $db55_hora = null; 
   var $db55_usuario = 0; 
   var $db55_nomearq = null; 
   var $db55_obs = null; 
   var $db55_conteudo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db55_sequencial = int4 = Sequencial 
                 db55_layouttxt = int4 = Código do layout 
                 db55_seqlayout = int4 = Sequencia dentro do layout 
                 db55_data = date = Data 
                 db55_hora = char(5) = Hora 
                 db55_usuario = int4 = Cod. Usuário 
                 db55_nomearq = varchar(100) = Nome do arquivo 
                 db55_obs = text = Observacoes 
                 db55_conteudo = text = Conteudo do txt 
                 ";
   //funcao construtor da classe 
   function cl_db_layouttxtgeracao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_layouttxtgeracao"); 
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
       $this->db55_sequencial = ($this->db55_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db55_sequencial"]:$this->db55_sequencial);
       $this->db55_layouttxt = ($this->db55_layouttxt == ""?@$GLOBALS["HTTP_POST_VARS"]["db55_layouttxt"]:$this->db55_layouttxt);
       $this->db55_seqlayout = ($this->db55_seqlayout == ""?@$GLOBALS["HTTP_POST_VARS"]["db55_seqlayout"]:$this->db55_seqlayout);
       if($this->db55_data == ""){
         $this->db55_data_dia = ($this->db55_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["db55_data_dia"]:$this->db55_data_dia);
         $this->db55_data_mes = ($this->db55_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["db55_data_mes"]:$this->db55_data_mes);
         $this->db55_data_ano = ($this->db55_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["db55_data_ano"]:$this->db55_data_ano);
         if($this->db55_data_dia != ""){
            $this->db55_data = $this->db55_data_ano."-".$this->db55_data_mes."-".$this->db55_data_dia;
         }
       }
       $this->db55_hora = ($this->db55_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["db55_hora"]:$this->db55_hora);
       $this->db55_usuario = ($this->db55_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["db55_usuario"]:$this->db55_usuario);
       $this->db55_nomearq = ($this->db55_nomearq == ""?@$GLOBALS["HTTP_POST_VARS"]["db55_nomearq"]:$this->db55_nomearq);
       $this->db55_obs = ($this->db55_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["db55_obs"]:$this->db55_obs);
       $this->db55_conteudo = ($this->db55_conteudo == ""?@$GLOBALS["HTTP_POST_VARS"]["db55_conteudo"]:$this->db55_conteudo);
     }else{
       $this->db55_sequencial = ($this->db55_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db55_sequencial"]:$this->db55_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($db55_sequencial){ 
      $this->atualizacampos();
     if($this->db55_layouttxt == null ){ 
       $this->erro_sql = " Campo Código do layout nao Informado.";
       $this->erro_campo = "db55_layouttxt";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db55_seqlayout == null ){ 
       $this->erro_sql = " Campo Sequencia dentro do layout nao Informado.";
       $this->erro_campo = "db55_seqlayout";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db55_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "db55_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db55_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "db55_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db55_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "db55_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db55_sequencial == "" || $db55_sequencial == null ){
       $result = db_query("select nextval('db_layouttxtgeracao_db55_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_layouttxtgeracao_db55_sequencial_seq do campo: db55_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db55_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_layouttxtgeracao_db55_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db55_sequencial)){
         $this->erro_sql = " Campo db55_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db55_sequencial = $db55_sequencial; 
       }
     }
     if(($this->db55_sequencial == null) || ($this->db55_sequencial == "") ){ 
       $this->erro_sql = " Campo db55_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_layouttxtgeracao(
                                       db55_sequencial 
                                      ,db55_layouttxt 
                                      ,db55_seqlayout 
                                      ,db55_data 
                                      ,db55_hora 
                                      ,db55_usuario 
                                      ,db55_nomearq 
                                      ,db55_obs 
                                      ,db55_conteudo 
                       )
                values (
                                $this->db55_sequencial 
                               ,$this->db55_layouttxt 
                               ,$this->db55_seqlayout 
                               ,".($this->db55_data == "null" || $this->db55_data == ""?"null":"'".$this->db55_data."'")." 
                               ,'$this->db55_hora' 
                               ,$this->db55_usuario 
                               ,'$this->db55_nomearq' 
                               ,'$this->db55_obs' 
                               ,'$this->db55_conteudo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Geracao dos txts ($this->db55_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Geracao dos txts já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Geracao dos txts ($this->db55_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db55_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db55_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9448,'$this->db55_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1622,9448,'','".AddSlashes(pg_result($resaco,0,'db55_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1622,9447,'','".AddSlashes(pg_result($resaco,0,'db55_layouttxt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1622,9456,'','".AddSlashes(pg_result($resaco,0,'db55_seqlayout'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1622,9449,'','".AddSlashes(pg_result($resaco,0,'db55_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1622,9451,'','".AddSlashes(pg_result($resaco,0,'db55_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1622,9453,'','".AddSlashes(pg_result($resaco,0,'db55_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1622,9455,'','".AddSlashes(pg_result($resaco,0,'db55_nomearq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1622,9458,'','".AddSlashes(pg_result($resaco,0,'db55_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1622,9452,'','".AddSlashes(pg_result($resaco,0,'db55_conteudo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db55_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update db_layouttxtgeracao set ";
     $virgula = "";
     if(trim($this->db55_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db55_sequencial"])){ 
       $sql  .= $virgula." db55_sequencial = $this->db55_sequencial ";
       $virgula = ",";
       if(trim($this->db55_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "db55_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db55_layouttxt)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db55_layouttxt"])){ 
       $sql  .= $virgula." db55_layouttxt = $this->db55_layouttxt ";
       $virgula = ",";
       if(trim($this->db55_layouttxt) == null ){ 
         $this->erro_sql = " Campo Código do layout nao Informado.";
         $this->erro_campo = "db55_layouttxt";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db55_seqlayout)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db55_seqlayout"])){ 
       $sql  .= $virgula." db55_seqlayout = $this->db55_seqlayout ";
       $virgula = ",";
       if(trim($this->db55_seqlayout) == null ){ 
         $this->erro_sql = " Campo Sequencia dentro do layout nao Informado.";
         $this->erro_campo = "db55_seqlayout";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db55_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db55_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["db55_data_dia"] !="") ){ 
       $sql  .= $virgula." db55_data = '$this->db55_data' ";
       $virgula = ",";
       if(trim($this->db55_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "db55_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["db55_data_dia"])){ 
         $sql  .= $virgula." db55_data = null ";
         $virgula = ",";
         if(trim($this->db55_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "db55_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->db55_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db55_hora"])){ 
       $sql  .= $virgula." db55_hora = '$this->db55_hora' ";
       $virgula = ",";
       if(trim($this->db55_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "db55_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db55_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db55_usuario"])){ 
       $sql  .= $virgula." db55_usuario = $this->db55_usuario ";
       $virgula = ",";
       if(trim($this->db55_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "db55_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db55_nomearq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db55_nomearq"])){ 
       $sql  .= $virgula." db55_nomearq = '$this->db55_nomearq' ";
       $virgula = ",";
     }
     if(trim($this->db55_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db55_obs"])){ 
       $sql  .= $virgula." db55_obs = '$this->db55_obs' ";
       $virgula = ",";
     }
     if(trim($this->db55_conteudo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db55_conteudo"])){ 
       $sql  .= $virgula." db55_conteudo = '$this->db55_conteudo' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($db55_sequencial!=null){
       $sql .= " db55_sequencial = $this->db55_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db55_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9448,'$this->db55_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db55_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1622,9448,'".AddSlashes(pg_result($resaco,$conresaco,'db55_sequencial'))."','$this->db55_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db55_layouttxt"]))
           $resac = db_query("insert into db_acount values($acount,1622,9447,'".AddSlashes(pg_result($resaco,$conresaco,'db55_layouttxt'))."','$this->db55_layouttxt',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db55_seqlayout"]))
           $resac = db_query("insert into db_acount values($acount,1622,9456,'".AddSlashes(pg_result($resaco,$conresaco,'db55_seqlayout'))."','$this->db55_seqlayout',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db55_data"]))
           $resac = db_query("insert into db_acount values($acount,1622,9449,'".AddSlashes(pg_result($resaco,$conresaco,'db55_data'))."','$this->db55_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db55_hora"]))
           $resac = db_query("insert into db_acount values($acount,1622,9451,'".AddSlashes(pg_result($resaco,$conresaco,'db55_hora'))."','$this->db55_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db55_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1622,9453,'".AddSlashes(pg_result($resaco,$conresaco,'db55_usuario'))."','$this->db55_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db55_nomearq"]))
           $resac = db_query("insert into db_acount values($acount,1622,9455,'".AddSlashes(pg_result($resaco,$conresaco,'db55_nomearq'))."','$this->db55_nomearq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db55_obs"]))
           $resac = db_query("insert into db_acount values($acount,1622,9458,'".AddSlashes(pg_result($resaco,$conresaco,'db55_obs'))."','$this->db55_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db55_conteudo"]))
           $resac = db_query("insert into db_acount values($acount,1622,9452,'".AddSlashes(pg_result($resaco,$conresaco,'db55_conteudo'))."','$this->db55_conteudo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Geracao dos txts nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db55_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Geracao dos txts nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db55_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db55_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db55_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db55_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9448,'$db55_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1622,9448,'','".AddSlashes(pg_result($resaco,$iresaco,'db55_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1622,9447,'','".AddSlashes(pg_result($resaco,$iresaco,'db55_layouttxt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1622,9456,'','".AddSlashes(pg_result($resaco,$iresaco,'db55_seqlayout'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1622,9449,'','".AddSlashes(pg_result($resaco,$iresaco,'db55_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1622,9451,'','".AddSlashes(pg_result($resaco,$iresaco,'db55_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1622,9453,'','".AddSlashes(pg_result($resaco,$iresaco,'db55_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1622,9455,'','".AddSlashes(pg_result($resaco,$iresaco,'db55_nomearq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1622,9458,'','".AddSlashes(pg_result($resaco,$iresaco,'db55_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1622,9452,'','".AddSlashes(pg_result($resaco,$iresaco,'db55_conteudo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_layouttxtgeracao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db55_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db55_sequencial = $db55_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Geracao dos txts nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db55_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Geracao dos txts nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db55_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db55_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_layouttxtgeracao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $db55_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_layouttxtgeracao ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = db_layouttxtgeracao.db55_usuario";
     $sql .= "      inner join db_layouttxt  on  db_layouttxt.db50_codigo = db_layouttxtgeracao.db55_layouttxt";
     $sql2 = "";
     if($dbwhere==""){
       if($db55_sequencial!=null ){
         $sql2 .= " where db_layouttxtgeracao.db55_sequencial = $db55_sequencial "; 
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
   function sql_query_file ( $db55_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_layouttxtgeracao ";
     $sql2 = "";
     if($dbwhere==""){
       if($db55_sequencial!=null ){
         $sql2 .= " where db_layouttxtgeracao.db55_sequencial = $db55_sequencial "; 
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