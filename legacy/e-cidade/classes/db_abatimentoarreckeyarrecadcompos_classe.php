<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: arrecadacao
//CLASSE DA ENTIDADE abatimentoarreckeyarrecadcompos
class cl_abatimentoarreckeyarrecadcompos { 
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
   var $k129_sequencial = 0; 
   var $k129_abatimentoarreckey = 0; 
   var $k129_arrecadcompos = 0; 
   var $k129_vlrhist = 0; 
   var $k129_correcao = 0; 
   var $k129_juros = 0; 
   var $k129_multa = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k129_sequencial = int4 = Sequencial 
                 k129_abatimentoarreckey = int4 = Abatimento Arreckey 
                 k129_arrecadcompos = int4 = Composição do Débito 
                 k129_vlrhist = numeric(15,2) = Valor Histórico 
                 k129_correcao = numeric(15,2) = Correção 
                 k129_juros = numeric(15,2) = Juros 
                 k129_multa = numeric(15,2) = Multa 
                 ";
   //funcao construtor da classe 
   function cl_abatimentoarreckeyarrecadcompos() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("abatimentoarreckeyarrecadcompos"); 
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
       $this->k129_sequencial = ($this->k129_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k129_sequencial"]:$this->k129_sequencial);
       $this->k129_abatimentoarreckey = ($this->k129_abatimentoarreckey == ""?@$GLOBALS["HTTP_POST_VARS"]["k129_abatimentoarreckey"]:$this->k129_abatimentoarreckey);
       $this->k129_arrecadcompos = ($this->k129_arrecadcompos == ""?@$GLOBALS["HTTP_POST_VARS"]["k129_arrecadcompos"]:$this->k129_arrecadcompos);
       $this->k129_vlrhist = ($this->k129_vlrhist == ""?@$GLOBALS["HTTP_POST_VARS"]["k129_vlrhist"]:$this->k129_vlrhist);
       $this->k129_correcao = ($this->k129_correcao == ""?@$GLOBALS["HTTP_POST_VARS"]["k129_correcao"]:$this->k129_correcao);
       $this->k129_juros = ($this->k129_juros == ""?@$GLOBALS["HTTP_POST_VARS"]["k129_juros"]:$this->k129_juros);
       $this->k129_multa = ($this->k129_multa == ""?@$GLOBALS["HTTP_POST_VARS"]["k129_multa"]:$this->k129_multa);
     }else{
       $this->k129_sequencial = ($this->k129_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k129_sequencial"]:$this->k129_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k129_sequencial){ 
      $this->atualizacampos();
     if($this->k129_abatimentoarreckey == null ){ 
       $this->erro_sql = " Campo Abatimento Arreckey nao Informado.";
       $this->erro_campo = "k129_abatimentoarreckey";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k129_arrecadcompos == null ){ 
       $this->erro_sql = " Campo Composição do Débito nao Informado.";
       $this->erro_campo = "k129_arrecadcompos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k129_vlrhist == null ){ 
       $this->erro_sql = " Campo Valor Histórico nao Informado.";
       $this->erro_campo = "k129_vlrhist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k129_correcao == null ){ 
       $this->erro_sql = " Campo Correção nao Informado.";
       $this->erro_campo = "k129_correcao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k129_juros == null ){ 
       $this->erro_sql = " Campo Juros nao Informado.";
       $this->erro_campo = "k129_juros";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k129_multa == null ){ 
       $this->erro_sql = " Campo Multa nao Informado.";
       $this->erro_campo = "k129_multa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k129_sequencial == "" || $k129_sequencial == null ){
       $result = db_query("select nextval('abatimentoarreckeyarrecadcompos_k129_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: abatimentoarreckeyarrecadcompos_k129_sequencial_seq do campo: k129_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k129_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from abatimentoarreckeyarrecadcompos_k129_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k129_sequencial)){
         $this->erro_sql = " Campo k129_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k129_sequencial = $k129_sequencial; 
       }
     }
     if(($this->k129_sequencial == null) || ($this->k129_sequencial == "") ){ 
       $this->erro_sql = " Campo k129_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into abatimentoarreckeyarrecadcompos(
                                       k129_sequencial 
                                      ,k129_abatimentoarreckey 
                                      ,k129_arrecadcompos 
                                      ,k129_vlrhist 
                                      ,k129_correcao 
                                      ,k129_juros 
                                      ,k129_multa 
                       )
                values (
                                $this->k129_sequencial 
                               ,$this->k129_abatimentoarreckey 
                               ,$this->k129_arrecadcompos 
                               ,$this->k129_vlrhist 
                               ,$this->k129_correcao 
                               ,$this->k129_juros 
                               ,$this->k129_multa 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Abatimento da Composição do Débito ($this->k129_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Abatimento da Composição do Débito já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Abatimento da Composição do Débito ($this->k129_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k129_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k129_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18125,'$this->k129_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3205,18125,'','".AddSlashes(pg_result($resaco,0,'k129_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3205,18126,'','".AddSlashes(pg_result($resaco,0,'k129_abatimentoarreckey'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3205,18127,'','".AddSlashes(pg_result($resaco,0,'k129_arrecadcompos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3205,18128,'','".AddSlashes(pg_result($resaco,0,'k129_vlrhist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3205,18129,'','".AddSlashes(pg_result($resaco,0,'k129_correcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3205,18130,'','".AddSlashes(pg_result($resaco,0,'k129_juros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3205,18131,'','".AddSlashes(pg_result($resaco,0,'k129_multa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k129_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update abatimentoarreckeyarrecadcompos set ";
     $virgula = "";
     if(trim($this->k129_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k129_sequencial"])){ 
       $sql  .= $virgula." k129_sequencial = $this->k129_sequencial ";
       $virgula = ",";
       if(trim($this->k129_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "k129_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k129_abatimentoarreckey)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k129_abatimentoarreckey"])){ 
       $sql  .= $virgula." k129_abatimentoarreckey = $this->k129_abatimentoarreckey ";
       $virgula = ",";
       if(trim($this->k129_abatimentoarreckey) == null ){ 
         $this->erro_sql = " Campo Abatimento Arreckey nao Informado.";
         $this->erro_campo = "k129_abatimentoarreckey";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k129_arrecadcompos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k129_arrecadcompos"])){ 
       $sql  .= $virgula." k129_arrecadcompos = $this->k129_arrecadcompos ";
       $virgula = ",";
       if(trim($this->k129_arrecadcompos) == null ){ 
         $this->erro_sql = " Campo Composição do Débito nao Informado.";
         $this->erro_campo = "k129_arrecadcompos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k129_vlrhist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k129_vlrhist"])){ 
       $sql  .= $virgula." k129_vlrhist = $this->k129_vlrhist ";
       $virgula = ",";
       if(trim($this->k129_vlrhist) == null ){ 
         $this->erro_sql = " Campo Valor Histórico nao Informado.";
         $this->erro_campo = "k129_vlrhist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k129_correcao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k129_correcao"])){ 
       $sql  .= $virgula." k129_correcao = $this->k129_correcao ";
       $virgula = ",";
       if(trim($this->k129_correcao) == null ){ 
         $this->erro_sql = " Campo Correção nao Informado.";
         $this->erro_campo = "k129_correcao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k129_juros)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k129_juros"])){ 
       $sql  .= $virgula." k129_juros = $this->k129_juros ";
       $virgula = ",";
       if(trim($this->k129_juros) == null ){ 
         $this->erro_sql = " Campo Juros nao Informado.";
         $this->erro_campo = "k129_juros";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k129_multa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k129_multa"])){ 
       $sql  .= $virgula." k129_multa = $this->k129_multa ";
       $virgula = ",";
       if(trim($this->k129_multa) == null ){ 
         $this->erro_sql = " Campo Multa nao Informado.";
         $this->erro_campo = "k129_multa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k129_sequencial!=null){
       $sql .= " k129_sequencial = $this->k129_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k129_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18125,'$this->k129_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k129_sequencial"]) || $this->k129_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3205,18125,'".AddSlashes(pg_result($resaco,$conresaco,'k129_sequencial'))."','$this->k129_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k129_abatimentoarreckey"]) || $this->k129_abatimentoarreckey != "")
           $resac = db_query("insert into db_acount values($acount,3205,18126,'".AddSlashes(pg_result($resaco,$conresaco,'k129_abatimentoarreckey'))."','$this->k129_abatimentoarreckey',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k129_arrecadcompos"]) || $this->k129_arrecadcompos != "")
           $resac = db_query("insert into db_acount values($acount,3205,18127,'".AddSlashes(pg_result($resaco,$conresaco,'k129_arrecadcompos'))."','$this->k129_arrecadcompos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k129_vlrhist"]) || $this->k129_vlrhist != "")
           $resac = db_query("insert into db_acount values($acount,3205,18128,'".AddSlashes(pg_result($resaco,$conresaco,'k129_vlrhist'))."','$this->k129_vlrhist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k129_correcao"]) || $this->k129_correcao != "")
           $resac = db_query("insert into db_acount values($acount,3205,18129,'".AddSlashes(pg_result($resaco,$conresaco,'k129_correcao'))."','$this->k129_correcao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k129_juros"]) || $this->k129_juros != "")
           $resac = db_query("insert into db_acount values($acount,3205,18130,'".AddSlashes(pg_result($resaco,$conresaco,'k129_juros'))."','$this->k129_juros',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k129_multa"]) || $this->k129_multa != "")
           $resac = db_query("insert into db_acount values($acount,3205,18131,'".AddSlashes(pg_result($resaco,$conresaco,'k129_multa'))."','$this->k129_multa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Abatimento da Composição do Débito nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k129_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Abatimento da Composição do Débito nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k129_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k129_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k129_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k129_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18125,'$k129_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3205,18125,'','".AddSlashes(pg_result($resaco,$iresaco,'k129_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3205,18126,'','".AddSlashes(pg_result($resaco,$iresaco,'k129_abatimentoarreckey'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3205,18127,'','".AddSlashes(pg_result($resaco,$iresaco,'k129_arrecadcompos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3205,18128,'','".AddSlashes(pg_result($resaco,$iresaco,'k129_vlrhist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3205,18129,'','".AddSlashes(pg_result($resaco,$iresaco,'k129_correcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3205,18130,'','".AddSlashes(pg_result($resaco,$iresaco,'k129_juros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3205,18131,'','".AddSlashes(pg_result($resaco,$iresaco,'k129_multa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from abatimentoarreckeyarrecadcompos
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k129_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k129_sequencial = $k129_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Abatimento da Composição do Débito nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k129_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Abatimento da Composição do Débito nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k129_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k129_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:abatimentoarreckeyarrecadcompos";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k129_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from abatimentoarreckeyarrecadcompos ";
     $sql .= "      inner join arrecadcompos  on  arrecadcompos.k00_sequencial = abatimentoarreckeyarrecadcompos.k129_arrecadcompos";
     $sql .= "      inner join abatimentoarreckey  on  abatimentoarreckey.k128_sequencial = abatimentoarreckeyarrecadcompos.k129_abatimentoarreckey";
     $sql .= "      inner join arreckey  on  arreckey.k00_sequencial = arrecadcompos.k00_arreckey";
     $sql .= "      inner join arreckey  as a on   a.k00_sequencial = abatimentoarreckey.k128_arreckey";
     $sql .= "      inner join abatimento  as b on   b.k125_sequencial = abatimentoarreckey.k128_abatimento";
     $sql2 = "";
     if($dbwhere==""){
       if($k129_sequencial!=null ){
         $sql2 .= " where abatimentoarreckeyarrecadcompos.k129_sequencial = $k129_sequencial "; 
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
   function sql_query_file ( $k129_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from abatimentoarreckeyarrecadcompos ";
     $sql2 = "";
     if($dbwhere==""){
       if($k129_sequencial!=null ){
         $sql2 .= " where abatimentoarreckeyarrecadcompos.k129_sequencial = $k129_sequencial "; 
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