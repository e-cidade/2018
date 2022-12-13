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

//MODULO: ouvidoria
//CLASSE DA ENTIDADE ouvidoriaatendimentodoc
class cl_ouvidoriaatendimentodoc { 
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
   var $ov19_sequencial = 0; 
   var $ov19_procdoc = 0; 
   var $ov19_ouvidoriaatendimento = 0; 
   var $ov19_entregue = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ov19_sequencial = int4 = Sequencial 
                 ov19_procdoc = int4 = Documento do Processo 
                 ov19_ouvidoriaatendimento = int4 = Atendimento 
                 ov19_entregue = bool = Entregue 
                 ";
   //funcao construtor da classe 
   function cl_ouvidoriaatendimentodoc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("ouvidoriaatendimentodoc"); 
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
       $this->ov19_sequencial = ($this->ov19_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ov19_sequencial"]:$this->ov19_sequencial);
       $this->ov19_procdoc = ($this->ov19_procdoc == ""?@$GLOBALS["HTTP_POST_VARS"]["ov19_procdoc"]:$this->ov19_procdoc);
       $this->ov19_ouvidoriaatendimento = ($this->ov19_ouvidoriaatendimento == ""?@$GLOBALS["HTTP_POST_VARS"]["ov19_ouvidoriaatendimento"]:$this->ov19_ouvidoriaatendimento);
       $this->ov19_entregue = ($this->ov19_entregue == "f"?@$GLOBALS["HTTP_POST_VARS"]["ov19_entregue"]:$this->ov19_entregue);
     }else{
       $this->ov19_sequencial = ($this->ov19_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ov19_sequencial"]:$this->ov19_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ov19_sequencial){ 
      $this->atualizacampos();
     if($this->ov19_procdoc == null ){ 
       $this->erro_sql = " Campo Documento do Processo nao Informado.";
       $this->erro_campo = "ov19_procdoc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ov19_ouvidoriaatendimento == null ){ 
       $this->erro_sql = " Campo Atendimento nao Informado.";
       $this->erro_campo = "ov19_ouvidoriaatendimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ov19_entregue == null ){ 
       $this->erro_sql = " Campo Entregue nao Informado.";
       $this->erro_campo = "ov19_entregue";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ov19_sequencial == "" || $ov19_sequencial == null ){
       $result = db_query("select nextval('ouvidoriaatendimentodoc_ov19_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: ouvidoriaatendimentodoc_ov19_sequencial_seq do campo: ov19_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ov19_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from ouvidoriaatendimentodoc_ov19_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ov19_sequencial)){
         $this->erro_sql = " Campo ov19_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ov19_sequencial = $ov19_sequencial; 
       }
     }
     if(($this->ov19_sequencial == null) || ($this->ov19_sequencial == "") ){ 
       $this->erro_sql = " Campo ov19_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into ouvidoriaatendimentodoc(
                                       ov19_sequencial 
                                      ,ov19_procdoc 
                                      ,ov19_ouvidoriaatendimento 
                                      ,ov19_entregue 
                       )
                values (
                                $this->ov19_sequencial 
                               ,$this->ov19_procdoc 
                               ,$this->ov19_ouvidoriaatendimento 
                               ,'$this->ov19_entregue' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Documentos do Atendimento de Ouvidoria ($this->ov19_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Documentos do Atendimento de Ouvidoria já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Documentos do Atendimento de Ouvidoria ($this->ov19_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ov19_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ov19_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14819,'$this->ov19_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2608,14819,'','".AddSlashes(pg_result($resaco,0,'ov19_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2608,14820,'','".AddSlashes(pg_result($resaco,0,'ov19_procdoc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2608,14821,'','".AddSlashes(pg_result($resaco,0,'ov19_ouvidoriaatendimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2608,14822,'','".AddSlashes(pg_result($resaco,0,'ov19_entregue'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ov19_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update ouvidoriaatendimentodoc set ";
     $virgula = "";
     if(trim($this->ov19_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov19_sequencial"])){ 
       $sql  .= $virgula." ov19_sequencial = $this->ov19_sequencial ";
       $virgula = ",";
       if(trim($this->ov19_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ov19_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov19_procdoc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov19_procdoc"])){ 
       $sql  .= $virgula." ov19_procdoc = $this->ov19_procdoc ";
       $virgula = ",";
       if(trim($this->ov19_procdoc) == null ){ 
         $this->erro_sql = " Campo Documento do Processo nao Informado.";
         $this->erro_campo = "ov19_procdoc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov19_ouvidoriaatendimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov19_ouvidoriaatendimento"])){ 
       $sql  .= $virgula." ov19_ouvidoriaatendimento = $this->ov19_ouvidoriaatendimento ";
       $virgula = ",";
       if(trim($this->ov19_ouvidoriaatendimento) == null ){ 
         $this->erro_sql = " Campo Atendimento nao Informado.";
         $this->erro_campo = "ov19_ouvidoriaatendimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov19_entregue)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov19_entregue"])){ 
       $sql  .= $virgula." ov19_entregue = '$this->ov19_entregue' ";
       $virgula = ",";
       if(trim($this->ov19_entregue) == null ){ 
         $this->erro_sql = " Campo Entregue nao Informado.";
         $this->erro_campo = "ov19_entregue";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ov19_sequencial!=null){
       $sql .= " ov19_sequencial = $this->ov19_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ov19_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14819,'$this->ov19_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov19_sequencial"]) || $this->ov19_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2608,14819,'".AddSlashes(pg_result($resaco,$conresaco,'ov19_sequencial'))."','$this->ov19_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov19_procdoc"]) || $this->ov19_procdoc != "")
           $resac = db_query("insert into db_acount values($acount,2608,14820,'".AddSlashes(pg_result($resaco,$conresaco,'ov19_procdoc'))."','$this->ov19_procdoc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov19_ouvidoriaatendimento"]) || $this->ov19_ouvidoriaatendimento != "")
           $resac = db_query("insert into db_acount values($acount,2608,14821,'".AddSlashes(pg_result($resaco,$conresaco,'ov19_ouvidoriaatendimento'))."','$this->ov19_ouvidoriaatendimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov19_entregue"]) || $this->ov19_entregue != "")
           $resac = db_query("insert into db_acount values($acount,2608,14822,'".AddSlashes(pg_result($resaco,$conresaco,'ov19_entregue'))."','$this->ov19_entregue',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Documentos do Atendimento de Ouvidoria nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ov19_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Documentos do Atendimento de Ouvidoria nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ov19_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ov19_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ov19_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ov19_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14819,'$ov19_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2608,14819,'','".AddSlashes(pg_result($resaco,$iresaco,'ov19_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2608,14820,'','".AddSlashes(pg_result($resaco,$iresaco,'ov19_procdoc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2608,14821,'','".AddSlashes(pg_result($resaco,$iresaco,'ov19_ouvidoriaatendimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2608,14822,'','".AddSlashes(pg_result($resaco,$iresaco,'ov19_entregue'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from ouvidoriaatendimentodoc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ov19_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ov19_sequencial = $ov19_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Documentos do Atendimento de Ouvidoria nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ov19_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Documentos do Atendimento de Ouvidoria nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ov19_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ov19_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:ouvidoriaatendimentodoc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ov19_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ouvidoriaatendimentodoc ";
     $sql .= "      inner join procdoc  on  procdoc.p56_coddoc = ouvidoriaatendimentodoc.ov19_procdoc";
     $sql .= "      inner join ouvidoriaatendimento  on  ouvidoriaatendimento.ov01_sequencial = ouvidoriaatendimentodoc.ov19_ouvidoriaatendimento";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = ouvidoriaatendimento.ov01_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = ouvidoriaatendimento.ov01_depart";
     $sql .= "      inner join tipoproc  on  tipoproc.p51_codigo = ouvidoriaatendimento.ov01_tipoprocesso";
     $sql .= "      inner join tipoidentificacao  on  tipoidentificacao.ov05_sequencial = ouvidoriaatendimento.ov01_tipoidentificacao";
     $sql .= "      inner join formareclamacao  on  formareclamacao.p42_sequencial = ouvidoriaatendimento.ov01_formareclamacao";
     $sql .= "      inner join situacaoouvidoriaatendimento  on  situacaoouvidoriaatendimento.ov18_sequencial = ouvidoriaatendimento.ov01_situacaoouvidoriaatendimento";
     $sql2 = "";
     if($dbwhere==""){
       if($ov19_sequencial!=null ){
         $sql2 .= " where ouvidoriaatendimentodoc.ov19_sequencial = $ov19_sequencial "; 
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
   function sql_query_file ( $ov19_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ouvidoriaatendimentodoc ";
     $sql2 = "";
     if($dbwhere==""){
       if($ov19_sequencial!=null ){
         $sql2 .= " where ouvidoriaatendimentodoc.ov19_sequencial = $ov19_sequencial "; 
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