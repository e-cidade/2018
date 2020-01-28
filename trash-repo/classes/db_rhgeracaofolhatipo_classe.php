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

//MODULO: pessoal
//CLASSE DA ENTIDADE rhgeracaofolhatipo
class cl_rhgeracaofolhatipo { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $rh103_sequencial = 0; 
   var $rh103_rhgeracaofolhareg = 0; 
   var $rh103_tipofolha = 0; 
   var $rh103_complementar = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh103_sequencial = int4 = C�digo Sequencial 
                 rh103_rhgeracaofolhareg = int4 = rh103_rhgeracaofolhareg 
                 rh103_tipofolha = int4 = Tipo Folha 
                 rh103_complementar = int4 = Complementar 
                 ";
   //funcao construtor da classe 
   function cl_rhgeracaofolhatipo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhgeracaofolhatipo"); 
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
       $this->rh103_sequencial = ($this->rh103_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh103_sequencial"]:$this->rh103_sequencial);
       $this->rh103_rhgeracaofolhareg = ($this->rh103_rhgeracaofolhareg == ""?@$GLOBALS["HTTP_POST_VARS"]["rh103_rhgeracaofolhareg"]:$this->rh103_rhgeracaofolhareg);
       $this->rh103_tipofolha = ($this->rh103_tipofolha == ""?@$GLOBALS["HTTP_POST_VARS"]["rh103_tipofolha"]:$this->rh103_tipofolha);
       $this->rh103_complementar = ($this->rh103_complementar == ""?@$GLOBALS["HTTP_POST_VARS"]["rh103_complementar"]:$this->rh103_complementar);
     }else{
       $this->rh103_sequencial = ($this->rh103_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh103_sequencial"]:$this->rh103_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh103_sequencial){ 
      $this->atualizacampos();
     if($this->rh103_rhgeracaofolhareg == null ){ 
       $this->erro_sql = " Campo rh103_rhgeracaofolhareg nao Informado.";
       $this->erro_campo = "rh103_rhgeracaofolhareg";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh103_tipofolha == null ){ 
       $this->erro_sql = " Campo Tipo Folha nao Informado.";
       $this->erro_campo = "rh103_tipofolha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh103_complementar == null ){ 
       $this->erro_sql = " Campo Complementar nao Informado.";
       $this->erro_campo = "rh103_complementar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh103_sequencial == "" || $rh103_sequencial == null ){
       $result = @pg_query("select nextval('rhgeracaofolhatipo_rh103_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhgeracaofolhatipo_rh103_sequencial_seq do campo: rh103_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh103_sequencial = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from rhgeracaofolhatipo_rh103_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh103_sequencial)){
         $this->erro_sql = " Campo rh103_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh103_sequencial = $rh103_sequencial; 
       }
     }
     if(($this->rh103_sequencial == null) || ($this->rh103_sequencial == "") ){ 
       $this->erro_sql = " Campo rh103_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $result = @pg_query("insert into rhgeracaofolhatipo(
                                       rh103_sequencial 
                                      ,rh103_rhgeracaofolhareg 
                                      ,rh103_tipofolha 
                                      ,rh103_complementar 
                       )
                values (
                                $this->rh103_sequencial 
                               ,$this->rh103_rhgeracaofolhareg 
                               ,$this->rh103_tipofolha 
                               ,$this->rh103_complementar 
                      )");
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "rhgeracaofolhatipo ($this->rh103_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "rhgeracaofolhatipo j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "rhgeracaofolhatipo ($this->rh103_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh103_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $resaco = $this->sql_record($this->sql_query_file($this->rh103_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,18081,'$this->rh103_sequencial','I')");
       $resac = pg_query("insert into db_acount values($acount,3196,18081,'','".pg_result($resaco,0,'rh103_sequencial')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,3196,18133,'','".pg_result($resaco,0,'rh103_rhgeracaofolhareg')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,3196,18083,'','".pg_result($resaco,0,'rh103_tipofolha')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,3196,18084,'','".pg_result($resaco,0,'rh103_complementar')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh103_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhgeracaofolhatipo set ";
     $virgula = "";
     if(trim($this->rh103_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh103_sequencial"])){ 
        if(trim($this->rh103_sequencial)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh103_sequencial"])){ 
           $this->rh103_sequencial = "0" ; 
        } 
       $sql  .= $virgula." rh103_sequencial = $this->rh103_sequencial ";
       $virgula = ",";
       if(trim($this->rh103_sequencial) == null ){ 
         $this->erro_sql = " Campo C�digo Sequencial nao Informado.";
         $this->erro_campo = "rh103_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh103_rhgeracaofolhareg)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh103_rhgeracaofolhareg"])){ 
        if(trim($this->rh103_rhgeracaofolhareg)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh103_rhgeracaofolhareg"])){ 
           $this->rh103_rhgeracaofolhareg = "0" ; 
        } 
       $sql  .= $virgula." rh103_rhgeracaofolhareg = $this->rh103_rhgeracaofolhareg ";
       $virgula = ",";
       if(trim($this->rh103_rhgeracaofolhareg) == null ){ 
         $this->erro_sql = " Campo rh103_rhgeracaofolhareg nao Informado.";
         $this->erro_campo = "rh103_rhgeracaofolhareg";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh103_tipofolha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh103_tipofolha"])){ 
        if(trim($this->rh103_tipofolha)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh103_tipofolha"])){ 
           $this->rh103_tipofolha = "0" ; 
        } 
       $sql  .= $virgula." rh103_tipofolha = $this->rh103_tipofolha ";
       $virgula = ",";
       if(trim($this->rh103_tipofolha) == null ){ 
         $this->erro_sql = " Campo Tipo Folha nao Informado.";
         $this->erro_campo = "rh103_tipofolha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh103_complementar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh103_complementar"])){ 
        if(trim($this->rh103_complementar)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh103_complementar"])){ 
           $this->rh103_complementar = "0" ; 
        } 
       $sql  .= $virgula." rh103_complementar = $this->rh103_complementar ";
       $virgula = ",";
       if(trim($this->rh103_complementar) == null ){ 
         $this->erro_sql = " Campo Complementar nao Informado.";
         $this->erro_campo = "rh103_complementar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where  rh103_sequencial = $this->rh103_sequencial
";
     $resaco = $this->sql_record($this->sql_query_file($this->rh103_sequencial));
     if($this->numrows>0){       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,18081,'$this->rh103_sequencial','A')");
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh103_sequencial"]))
         $resac = pg_query("insert into db_acount values($acount,3196,18081,'".pg_result($resaco,0,'rh103_sequencial')."','$this->rh103_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh103_rhgeracaofolhareg"]))
         $resac = pg_query("insert into db_acount values($acount,3196,18133,'".pg_result($resaco,0,'rh103_rhgeracaofolhareg')."','$this->rh103_rhgeracaofolhareg',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh103_tipofolha"]))
         $resac = pg_query("insert into db_acount values($acount,3196,18083,'".pg_result($resaco,0,'rh103_tipofolha')."','$this->rh103_tipofolha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh103_complementar"]))
         $resac = pg_query("insert into db_acount values($acount,3196,18084,'".pg_result($resaco,0,'rh103_complementar')."','$this->rh103_complementar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhgeracaofolhatipo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh103_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rhgeracaofolhatipo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh103_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh103_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh103_sequencial=null) { 
     $this->atualizacampos(true);
     $resaco = $this->sql_record($this->sql_query_file($this->rh103_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,18081,'$this->rh103_sequencial','E')");
       $resac = pg_query("insert into db_acount values($acount,3196,18081,'','".pg_result($resaco,0,'rh103_sequencial')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,3196,18133,'','".pg_result($resaco,0,'rh103_rhgeracaofolhareg')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,3196,18083,'','".pg_result($resaco,0,'rh103_tipofolha')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,3196,18084,'','".pg_result($resaco,0,'rh103_complementar')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     $sql = " delete from rhgeracaofolhatipo
                    where ";
     $sql2 = "";
      if($this->rh103_sequencial != ""){
      if($sql2!=""){
        $sql2 .= " and ";
      }
      $sql2 .= " rh103_sequencial = $this->rh103_sequencial ";
}
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhgeracaofolhatipo nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$this->rh103_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rhgeracaofolhatipo nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh103_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh103_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = @pg_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Dados do Grupo nao Encontrado";
        $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh103_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhgeracaofolhatipo ";
     $sql .= "      inner join rhgeracaofolhareg  on  rhgeracaofolhareg.rh104_sequencial = rhgeracaofolhatipo.rh103_rhgeracaofolhareg";
     $sql .= "      inner join rhpessoalmov  on  rhpessoalmov.rh02_seqpes = rhgeracaofolhareg.rh104_seqpes and  rhpessoalmov.rh02_instit = rhgeracaofolhareg.rh104_instit";
     $sql .= "      inner join rhgeracaofolha  as a on   a.rh102_sequencial = rhgeracaofolhareg.rh104_rhgeracaofolha";
     $sql2 = "";
     if($dbwhere==""){
       if($rh103_sequencial!=null ){
         $sql2 .= " where rhgeracaofolhatipo.rh103_sequencial = $rh103_sequencial "; 
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
   function sql_query_file ( $rh103_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhgeracaofolhatipo ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh103_sequencial!=null ){
         $sql2 .= " where rhgeracaofolhatipo.rh103_sequencial = $rh103_sequencial "; 
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