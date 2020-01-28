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

//MODULO: orcamento
//CLASSE DA ENTIDADE orcparamseqsigap
class cl_orcparamseqsigap { 
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
   var $o141_sequencial = 0; 
   var $o141_contasigap = null; 
   var $o141_descricao = null; 
   var $o141_estrutural = null; 
   var $o141_orcparamseq = 0; 
   var $o141_orcparamrel = 0; 
   var $o141_ano = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o141_sequencial = int4 = Código Sequencial 
                 o141_contasigap = varchar(100) = Conta SIGAP 
                 o141_descricao = varchar(100) = Descrição 
                 o141_estrutural = varchar(20) = Estrutural 
                 o141_orcparamseq = int4 = Linha 
                 o141_orcparamrel = int4 = Relatório 
                 o141_ano = int4 = Ano 
                 ";
   //funcao construtor da classe 
   function cl_orcparamseqsigap() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcparamseqsigap"); 
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
       $this->o141_sequencial = ($this->o141_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o141_sequencial"]:$this->o141_sequencial);
       $this->o141_contasigap = ($this->o141_contasigap == ""?@$GLOBALS["HTTP_POST_VARS"]["o141_contasigap"]:$this->o141_contasigap);
       $this->o141_descricao = ($this->o141_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["o141_descricao"]:$this->o141_descricao);
       $this->o141_estrutural = ($this->o141_estrutural == ""?@$GLOBALS["HTTP_POST_VARS"]["o141_estrutural"]:$this->o141_estrutural);
       $this->o141_orcparamseq = ($this->o141_orcparamseq == ""?@$GLOBALS["HTTP_POST_VARS"]["o141_orcparamseq"]:$this->o141_orcparamseq);
       $this->o141_orcparamrel = ($this->o141_orcparamrel == ""?@$GLOBALS["HTTP_POST_VARS"]["o141_orcparamrel"]:$this->o141_orcparamrel);
       $this->o141_ano = ($this->o141_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["o141_ano"]:$this->o141_ano);
     }else{
       $this->o141_sequencial = ($this->o141_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o141_sequencial"]:$this->o141_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($o141_sequencial){ 
      $this->atualizacampos();
     if($this->o141_contasigap == null ){ 
       $this->erro_sql = " Campo Conta SIGAP nao Informado.";
       $this->erro_campo = "o141_contasigap";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o141_descricao == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "o141_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o141_estrutural == null ){ 
       $this->erro_sql = " Campo Estrutural nao Informado.";
       $this->erro_campo = "o141_estrutural";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o141_orcparamseq == null ){ 
       $this->erro_sql = " Campo Linha nao Informado.";
       $this->erro_campo = "o141_orcparamseq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o141_orcparamrel == null ){ 
       $this->erro_sql = " Campo Relatório nao Informado.";
       $this->erro_campo = "o141_orcparamrel";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o141_ano == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "o141_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o141_sequencial == "" || $o141_sequencial == null ){
       $result = db_query("select nextval('orcparamseqsigap_o141_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orcparamseqsigap_o141_sequencial_seq do campo: o141_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o141_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from orcparamseqsigap_o141_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o141_sequencial)){
         $this->erro_sql = " Campo o141_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o141_sequencial = $o141_sequencial; 
       }
     }
     if(($this->o141_sequencial == null) || ($this->o141_sequencial == "") ){ 
       $this->erro_sql = " Campo o141_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcparamseqsigap(
                                       o141_sequencial 
                                      ,o141_contasigap 
                                      ,o141_descricao 
                                      ,o141_estrutural 
                                      ,o141_orcparamseq 
                                      ,o141_orcparamrel 
                                      ,o141_ano 
                       )
                values (
                                $this->o141_sequencial 
                               ,'$this->o141_contasigap' 
                               ,'$this->o141_descricao' 
                               ,'$this->o141_estrutural' 
                               ,$this->o141_orcparamseq 
                               ,$this->o141_orcparamrel 
                               ,$this->o141_ano 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "orcparamseqsigap ($this->o141_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "orcparamseqsigap já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "orcparamseqsigap ($this->o141_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o141_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o141_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17821,'$this->o141_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3146,17821,'','".AddSlashes(pg_result($resaco,0,'o141_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3146,17822,'','".AddSlashes(pg_result($resaco,0,'o141_contasigap'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3146,17823,'','".AddSlashes(pg_result($resaco,0,'o141_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3146,17824,'','".AddSlashes(pg_result($resaco,0,'o141_estrutural'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3146,17825,'','".AddSlashes(pg_result($resaco,0,'o141_orcparamseq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3146,17826,'','".AddSlashes(pg_result($resaco,0,'o141_orcparamrel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3146,17827,'','".AddSlashes(pg_result($resaco,0,'o141_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o141_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update orcparamseqsigap set ";
     $virgula = "";
     if(trim($this->o141_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o141_sequencial"])){ 
       $sql  .= $virgula." o141_sequencial = $this->o141_sequencial ";
       $virgula = ",";
       if(trim($this->o141_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "o141_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o141_contasigap)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o141_contasigap"])){ 
       $sql  .= $virgula." o141_contasigap = '$this->o141_contasigap' ";
       $virgula = ",";
       if(trim($this->o141_contasigap) == null ){ 
         $this->erro_sql = " Campo Conta SIGAP nao Informado.";
         $this->erro_campo = "o141_contasigap";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o141_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o141_descricao"])){ 
       $sql  .= $virgula." o141_descricao = '$this->o141_descricao' ";
       $virgula = ",";
       if(trim($this->o141_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "o141_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o141_estrutural)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o141_estrutural"])){ 
       $sql  .= $virgula." o141_estrutural = '$this->o141_estrutural' ";
       $virgula = ",";
       if(trim($this->o141_estrutural) == null ){ 
         $this->erro_sql = " Campo Estrutural nao Informado.";
         $this->erro_campo = "o141_estrutural";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o141_orcparamseq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o141_orcparamseq"])){ 
       $sql  .= $virgula." o141_orcparamseq = $this->o141_orcparamseq ";
       $virgula = ",";
       if(trim($this->o141_orcparamseq) == null ){ 
         $this->erro_sql = " Campo Linha nao Informado.";
         $this->erro_campo = "o141_orcparamseq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o141_orcparamrel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o141_orcparamrel"])){ 
       $sql  .= $virgula." o141_orcparamrel = $this->o141_orcparamrel ";
       $virgula = ",";
       if(trim($this->o141_orcparamrel) == null ){ 
         $this->erro_sql = " Campo Relatório nao Informado.";
         $this->erro_campo = "o141_orcparamrel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o141_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o141_ano"])){ 
       $sql  .= $virgula." o141_ano = $this->o141_ano ";
       $virgula = ",";
       if(trim($this->o141_ano) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "o141_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o141_sequencial!=null){
       $sql .= " o141_sequencial = $this->o141_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o141_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17821,'$this->o141_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o141_sequencial"]) || $this->o141_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3146,17821,'".AddSlashes(pg_result($resaco,$conresaco,'o141_sequencial'))."','$this->o141_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o141_contasigap"]) || $this->o141_contasigap != "")
           $resac = db_query("insert into db_acount values($acount,3146,17822,'".AddSlashes(pg_result($resaco,$conresaco,'o141_contasigap'))."','$this->o141_contasigap',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o141_descricao"]) || $this->o141_descricao != "")
           $resac = db_query("insert into db_acount values($acount,3146,17823,'".AddSlashes(pg_result($resaco,$conresaco,'o141_descricao'))."','$this->o141_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o141_estrutural"]) || $this->o141_estrutural != "")
           $resac = db_query("insert into db_acount values($acount,3146,17824,'".AddSlashes(pg_result($resaco,$conresaco,'o141_estrutural'))."','$this->o141_estrutural',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o141_orcparamseq"]) || $this->o141_orcparamseq != "")
           $resac = db_query("insert into db_acount values($acount,3146,17825,'".AddSlashes(pg_result($resaco,$conresaco,'o141_orcparamseq'))."','$this->o141_orcparamseq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o141_orcparamrel"]) || $this->o141_orcparamrel != "")
           $resac = db_query("insert into db_acount values($acount,3146,17826,'".AddSlashes(pg_result($resaco,$conresaco,'o141_orcparamrel'))."','$this->o141_orcparamrel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o141_ano"]) || $this->o141_ano != "")
           $resac = db_query("insert into db_acount values($acount,3146,17827,'".AddSlashes(pg_result($resaco,$conresaco,'o141_ano'))."','$this->o141_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "orcparamseqsigap nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o141_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "orcparamseqsigap nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o141_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o141_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o141_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o141_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17821,'$o141_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3146,17821,'','".AddSlashes(pg_result($resaco,$iresaco,'o141_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3146,17822,'','".AddSlashes(pg_result($resaco,$iresaco,'o141_contasigap'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3146,17823,'','".AddSlashes(pg_result($resaco,$iresaco,'o141_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3146,17824,'','".AddSlashes(pg_result($resaco,$iresaco,'o141_estrutural'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3146,17825,'','".AddSlashes(pg_result($resaco,$iresaco,'o141_orcparamseq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3146,17826,'','".AddSlashes(pg_result($resaco,$iresaco,'o141_orcparamrel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3146,17827,'','".AddSlashes(pg_result($resaco,$iresaco,'o141_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcparamseqsigap
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o141_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o141_sequencial = $o141_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "orcparamseqsigap nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o141_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "orcparamseqsigap nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o141_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o141_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcparamseqsigap";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o141_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcparamseqsigap ";
     $sql .= "      inner join orcparamseq  on  orcparamseq.o69_codparamrel = orcparamseqsigap.o141_orcparamrel and  orcparamseq.o69_codseq = orcparamseqsigap.o141_orcparamseq";
     $sql .= "      inner join orcparamrel  on  orcparamrel.o42_codparrel = orcparamseq.o69_codparamrel";
     $sql2 = "";
     if($dbwhere==""){
       if($o141_sequencial!=null ){
         $sql2 .= " where orcparamseqsigap.o141_sequencial = $o141_sequencial "; 
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
   function sql_query_file ( $o141_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcparamseqsigap ";
     $sql2 = "";
     if($dbwhere==""){
       if($o141_sequencial!=null ){
         $sql2 .= " where orcparamseqsigap.o141_sequencial = $o141_sequencial "; 
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