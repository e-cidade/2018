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

//MODULO: recursoshumanos
//CLASSE DA ENTIDADE rhestagioavaliacao
class cl_rhestagioavaliacao { 
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
   var $h56_sequencial = 0; 
   var $h56_rhestagiocomissao = 0; 
   var $h56_rhestagioagenda = 0; 
   var $h56_data_dia = null; 
   var $h56_data_mes = null; 
   var $h56_data_ano = null; 
   var $h56_data = null; 
   var $h56_avaliador = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 h56_sequencial = int4 = Cód. Sequencial 
                 h56_rhestagiocomissao = int4 = Cód. Comissão 
                 h56_rhestagioagenda = int4 = Cód. Agenda 
                 h56_data = date = Data 
                 h56_avaliador = int4 = Avaliador 
                 ";
   //funcao construtor da classe 
   function cl_rhestagioavaliacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhestagioavaliacao"); 
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
       $this->h56_sequencial = ($this->h56_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["h56_sequencial"]:$this->h56_sequencial);
       $this->h56_rhestagiocomissao = ($this->h56_rhestagiocomissao == ""?@$GLOBALS["HTTP_POST_VARS"]["h56_rhestagiocomissao"]:$this->h56_rhestagiocomissao);
       $this->h56_rhestagioagenda = ($this->h56_rhestagioagenda == ""?@$GLOBALS["HTTP_POST_VARS"]["h56_rhestagioagenda"]:$this->h56_rhestagioagenda);
       if($this->h56_data == ""){
         $this->h56_data_dia = ($this->h56_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["h56_data_dia"]:$this->h56_data_dia);
         $this->h56_data_mes = ($this->h56_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["h56_data_mes"]:$this->h56_data_mes);
         $this->h56_data_ano = ($this->h56_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["h56_data_ano"]:$this->h56_data_ano);
         if($this->h56_data_dia != ""){
            $this->h56_data = $this->h56_data_ano."-".$this->h56_data_mes."-".$this->h56_data_dia;
         }
       }
       $this->h56_avaliador = ($this->h56_avaliador == ""?@$GLOBALS["HTTP_POST_VARS"]["h56_avaliador"]:$this->h56_avaliador);
     }else{
       $this->h56_sequencial = ($this->h56_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["h56_sequencial"]:$this->h56_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($h56_sequencial){ 
      $this->atualizacampos();
     if($this->h56_rhestagiocomissao == null ){ 
       $this->erro_sql = " Campo Cód. Comissão nao Informado.";
       $this->erro_campo = "h56_rhestagiocomissao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h56_rhestagioagenda == null ){ 
       $this->erro_sql = " Campo Cód. Agenda nao Informado.";
       $this->erro_campo = "h56_rhestagioagenda";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h56_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "h56_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h56_avaliador == null ){ 
       $this->erro_sql = " Campo Avaliador nao Informado.";
       $this->erro_campo = "h56_avaliador";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($h56_sequencial == "" || $h56_sequencial == null ){
       $result = db_query("select nextval('rhestagioavaliacao_h56_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhestagioavaliacao_h56_sequencial_seq do campo: h56_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->h56_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhestagioavaliacao_h56_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $h56_sequencial)){
         $this->erro_sql = " Campo h56_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->h56_sequencial = $h56_sequencial; 
       }
     }
     if(($this->h56_sequencial == null) || ($this->h56_sequencial == "") ){ 
       $this->erro_sql = " Campo h56_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhestagioavaliacao(
                                       h56_sequencial 
                                      ,h56_rhestagiocomissao 
                                      ,h56_rhestagioagenda 
                                      ,h56_data 
                                      ,h56_avaliador 
                       )
                values (
                                $this->h56_sequencial 
                               ,$this->h56_rhestagiocomissao 
                               ,$this->h56_rhestagioagenda 
                               ,".($this->h56_data == "null" || $this->h56_data == ""?"null":"'".$this->h56_data."'")." 
                               ,$this->h56_avaliador 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Avaliação de estágio ($this->h56_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Avaliação de estágio já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Avaliação de estágio ($this->h56_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h56_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->h56_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10888,'$this->h56_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1878,10888,'','".AddSlashes(pg_result($resaco,0,'h56_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1878,10889,'','".AddSlashes(pg_result($resaco,0,'h56_rhestagiocomissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1878,10890,'','".AddSlashes(pg_result($resaco,0,'h56_rhestagioagenda'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1878,10891,'','".AddSlashes(pg_result($resaco,0,'h56_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1878,10892,'','".AddSlashes(pg_result($resaco,0,'h56_avaliador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($h56_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhestagioavaliacao set ";
     $virgula = "";
     if(trim($this->h56_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h56_sequencial"])){ 
       $sql  .= $virgula." h56_sequencial = $this->h56_sequencial ";
       $virgula = ",";
       if(trim($this->h56_sequencial) == null ){ 
         $this->erro_sql = " Campo Cód. Sequencial nao Informado.";
         $this->erro_campo = "h56_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h56_rhestagiocomissao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h56_rhestagiocomissao"])){ 
       $sql  .= $virgula." h56_rhestagiocomissao = $this->h56_rhestagiocomissao ";
       $virgula = ",";
       if(trim($this->h56_rhestagiocomissao) == null ){ 
         $this->erro_sql = " Campo Cód. Comissão nao Informado.";
         $this->erro_campo = "h56_rhestagiocomissao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h56_rhestagioagenda)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h56_rhestagioagenda"])){ 
       $sql  .= $virgula." h56_rhestagioagenda = $this->h56_rhestagioagenda ";
       $virgula = ",";
       if(trim($this->h56_rhestagioagenda) == null ){ 
         $this->erro_sql = " Campo Cód. Agenda nao Informado.";
         $this->erro_campo = "h56_rhestagioagenda";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h56_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h56_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["h56_data_dia"] !="") ){ 
       $sql  .= $virgula." h56_data = '$this->h56_data' ";
       $virgula = ",";
       if(trim($this->h56_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "h56_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["h56_data_dia"])){ 
         $sql  .= $virgula." h56_data = null ";
         $virgula = ",";
         if(trim($this->h56_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "h56_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->h56_avaliador)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h56_avaliador"])){ 
       $sql  .= $virgula." h56_avaliador = $this->h56_avaliador ";
       $virgula = ",";
       if(trim($this->h56_avaliador) == null ){ 
         $this->erro_sql = " Campo Avaliador nao Informado.";
         $this->erro_campo = "h56_avaliador";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($h56_sequencial!=null){
       $sql .= " h56_sequencial = $this->h56_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->h56_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10888,'$this->h56_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h56_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1878,10888,'".AddSlashes(pg_result($resaco,$conresaco,'h56_sequencial'))."','$this->h56_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h56_rhestagiocomissao"]))
           $resac = db_query("insert into db_acount values($acount,1878,10889,'".AddSlashes(pg_result($resaco,$conresaco,'h56_rhestagiocomissao'))."','$this->h56_rhestagiocomissao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h56_rhestagioagenda"]))
           $resac = db_query("insert into db_acount values($acount,1878,10890,'".AddSlashes(pg_result($resaco,$conresaco,'h56_rhestagioagenda'))."','$this->h56_rhestagioagenda',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h56_data"]))
           $resac = db_query("insert into db_acount values($acount,1878,10891,'".AddSlashes(pg_result($resaco,$conresaco,'h56_data'))."','$this->h56_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h56_avaliador"]))
           $resac = db_query("insert into db_acount values($acount,1878,10892,'".AddSlashes(pg_result($resaco,$conresaco,'h56_avaliador'))."','$this->h56_avaliador',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avaliação de estágio nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->h56_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Avaliação de estágio nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->h56_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h56_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($h56_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($h56_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10888,'$h56_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1878,10888,'','".AddSlashes(pg_result($resaco,$iresaco,'h56_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1878,10889,'','".AddSlashes(pg_result($resaco,$iresaco,'h56_rhestagiocomissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1878,10890,'','".AddSlashes(pg_result($resaco,$iresaco,'h56_rhestagioagenda'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1878,10891,'','".AddSlashes(pg_result($resaco,$iresaco,'h56_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1878,10892,'','".AddSlashes(pg_result($resaco,$iresaco,'h56_avaliador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhestagioavaliacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($h56_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " h56_sequencial = $h56_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avaliação de estágio nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$h56_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Avaliação de estágio nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$h56_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$h56_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhestagioavaliacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $h56_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhestagioavaliacao ";
     $sql .= "      inner join rhestagiocomissao  on  rhestagiocomissao.h59_sequencial = rhestagioavaliacao.h56_rhestagiocomissao";
     $sql .= "      inner join rhestagioagendadata  on  rhestagioagendadata.h64_sequencial = rhestagioavaliacao.h56_rhestagioagenda";
     $sql .= "      inner join rhestagioagenda  as a on   a.h57_sequencial = rhestagioagendadata.h64_estagioagenda";
     $sql2 = "";
     if($dbwhere==""){
       if($h56_sequencial!=null ){
         $sql2 .= " where rhestagioavaliacao.h56_sequencial = $h56_sequencial "; 
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
   function sql_query_file ( $h56_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhestagioavaliacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($h56_sequencial!=null ){
         $sql2 .= " where rhestagioavaliacao.h56_sequencial = $h56_sequencial "; 
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
   function sql_query_comissao( $h60_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhestagioavaliacao ";
     $sql .= "      inner join rhestagioagendadata  on  h64_sequencial = h56_rhestagioagenda";
     $sql .= "      inner join rhestagiocomissao  on  h59_sequencial = h56_rhestagiocomissao";
     $sql .= "      inner join rhestagiocomissaomembro  on  h59_sequencial = h60_rhestagiocomissao";
     $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = rhestagiocomissaomembro.h60_regist";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      inner join rhestcivil  on  rhestcivil.rh08_estciv = rhpessoal.rh01_estciv";
     $sql .= "      inner join rhraca  on  rhraca.rh18_raca = rhpessoal.rh01_raca";
     $sql .= "      inner join rhfuncao  on  rhfuncao.rh37_funcao = rhpessoal.rh01_funcao";
     $sql .= "      inner join rhinstrucao  on  rhinstrucao.rh21_instru = rhpessoal.rh01_instru";
     $sql .= "      inner join rhnacionalidade  on  rhnacionalidade.rh06_nacionalidade = rhpessoal.rh01_nacion";
     $sql2 = "";
     if($dbwhere==""){
       if($h60_sequencial!=null ){
         $sql2 .= " where rhestagiocomissaomembro.h60_sequencial = $h60_sequencial "; 
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