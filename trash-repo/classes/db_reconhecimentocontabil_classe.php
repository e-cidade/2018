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

//MODULO: contabilidade
//CLASSE DA ENTIDADE reconhecimentocontabil
class cl_reconhecimentocontabil { 
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
   var $c112_sequencial = 0; 
   var $c112_reconhecimentocontabiltipo = 0; 
   var $c112_numcgm = 0; 
   var $c112_processoadm = null; 
   var $c112_valor = 0; 
   var $c112_estornado = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 c112_sequencial = int4 = Codigo do Reconhecimento Contabil 
                 c112_reconhecimentocontabiltipo = int4 = Tipos de Rec. Cont�bil 
                 c112_numcgm = int4 = Credor / Favorecido 
                 c112_processoadm = varchar(20) = Processo administrativo 
                 c112_valor = float8 = Valor 
                 c112_estornado = bool = Estornado 
                 ";
   //funcao construtor da classe 
   function cl_reconhecimentocontabil() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("reconhecimentocontabil"); 
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
       $this->c112_sequencial = ($this->c112_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c112_sequencial"]:$this->c112_sequencial);
       $this->c112_reconhecimentocontabiltipo = ($this->c112_reconhecimentocontabiltipo == ""?@$GLOBALS["HTTP_POST_VARS"]["c112_reconhecimentocontabiltipo"]:$this->c112_reconhecimentocontabiltipo);
       $this->c112_numcgm = ($this->c112_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["c112_numcgm"]:$this->c112_numcgm);
       $this->c112_processoadm = ($this->c112_processoadm == ""?@$GLOBALS["HTTP_POST_VARS"]["c112_processoadm"]:$this->c112_processoadm);
       $this->c112_valor = ($this->c112_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["c112_valor"]:$this->c112_valor);
       $this->c112_estornado = ($this->c112_estornado == "f"?@$GLOBALS["HTTP_POST_VARS"]["c112_estornado"]:$this->c112_estornado);
     }else{
       $this->c112_sequencial = ($this->c112_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c112_sequencial"]:$this->c112_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($c112_sequencial){ 
      $this->atualizacampos();
     if($this->c112_reconhecimentocontabiltipo == null ){ 
       $this->erro_sql = " Campo Tipos de Rec. Cont�bil n�o informado.";
       $this->erro_campo = "c112_reconhecimentocontabiltipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c112_numcgm == null ){ 
       $this->erro_sql = " Campo Credor / Favorecido n�o informado.";
       $this->erro_campo = "c112_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c112_valor == null ){ 
       $this->erro_sql = " Campo Valor n�o informado.";
       $this->erro_campo = "c112_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c112_estornado == null ){ 
       $this->erro_sql = " Campo Estornado n�o informado.";
       $this->erro_campo = "c112_estornado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($c112_sequencial == "" || $c112_sequencial == null ){
       $result = db_query("select nextval('reconhecimentocontabil_c112_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: reconhecimentocontabil_c112_sequencial_seq do campo: c112_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->c112_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from reconhecimentocontabil_c112_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $c112_sequencial)){
         $this->erro_sql = " Campo c112_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c112_sequencial = $c112_sequencial; 
       }
     }
     if(($this->c112_sequencial == null) || ($this->c112_sequencial == "") ){ 
       $this->erro_sql = " Campo c112_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into reconhecimentocontabil(
                                       c112_sequencial 
                                      ,c112_reconhecimentocontabiltipo 
                                      ,c112_numcgm 
                                      ,c112_processoadm 
                                      ,c112_valor 
                                      ,c112_estornado 
                       )
                values (
                                $this->c112_sequencial 
                               ,$this->c112_reconhecimentocontabiltipo 
                               ,$this->c112_numcgm 
                               ,'$this->c112_processoadm' 
                               ,$this->c112_valor 
                               ,'$this->c112_estornado' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Reconhecimento contabil ($this->c112_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Reconhecimento contabil j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Reconhecimento contabil ($this->c112_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c112_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->c112_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20201,'$this->c112_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3627,20201,'','".AddSlashes(pg_result($resaco,0,'c112_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3627,20202,'','".AddSlashes(pg_result($resaco,0,'c112_reconhecimentocontabiltipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3627,20203,'','".AddSlashes(pg_result($resaco,0,'c112_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3627,20204,'','".AddSlashes(pg_result($resaco,0,'c112_processoadm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3627,20205,'','".AddSlashes(pg_result($resaco,0,'c112_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3627,20206,'','".AddSlashes(pg_result($resaco,0,'c112_estornado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($c112_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update reconhecimentocontabil set ";
     $virgula = "";
     if(trim($this->c112_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c112_sequencial"])){ 
       $sql  .= $virgula." c112_sequencial = $this->c112_sequencial ";
       $virgula = ",";
       if(trim($this->c112_sequencial) == null ){ 
         $this->erro_sql = " Campo Codigo do Reconhecimento Contabil n�o informado.";
         $this->erro_campo = "c112_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c112_reconhecimentocontabiltipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c112_reconhecimentocontabiltipo"])){ 
       $sql  .= $virgula." c112_reconhecimentocontabiltipo = $this->c112_reconhecimentocontabiltipo ";
       $virgula = ",";
       if(trim($this->c112_reconhecimentocontabiltipo) == null ){ 
         $this->erro_sql = " Campo Tipos de Rec. Cont�bil n�o informado.";
         $this->erro_campo = "c112_reconhecimentocontabiltipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c112_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c112_numcgm"])){ 
       $sql  .= $virgula." c112_numcgm = $this->c112_numcgm ";
       $virgula = ",";
       if(trim($this->c112_numcgm) == null ){ 
         $this->erro_sql = " Campo Credor / Favorecido n�o informado.";
         $this->erro_campo = "c112_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c112_processoadm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c112_processoadm"])){ 
       $sql  .= $virgula." c112_processoadm = '$this->c112_processoadm' ";
       $virgula = ",";
     }
     if(trim($this->c112_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c112_valor"])){ 
       $sql  .= $virgula." c112_valor = $this->c112_valor ";
       $virgula = ",";
       if(trim($this->c112_valor) == null ){ 
         $this->erro_sql = " Campo Valor n�o informado.";
         $this->erro_campo = "c112_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c112_estornado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c112_estornado"])){ 
       $sql  .= $virgula." c112_estornado = '$this->c112_estornado' ";
       $virgula = ",";
       if(trim($this->c112_estornado) == null ){ 
         $this->erro_sql = " Campo Estornado n�o informado.";
         $this->erro_campo = "c112_estornado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c112_sequencial!=null){
       $sql .= " c112_sequencial = $this->c112_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->c112_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20201,'$this->c112_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["c112_sequencial"]) || $this->c112_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3627,20201,'".AddSlashes(pg_result($resaco,$conresaco,'c112_sequencial'))."','$this->c112_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["c112_reconhecimentocontabiltipo"]) || $this->c112_reconhecimentocontabiltipo != "")
             $resac = db_query("insert into db_acount values($acount,3627,20202,'".AddSlashes(pg_result($resaco,$conresaco,'c112_reconhecimentocontabiltipo'))."','$this->c112_reconhecimentocontabiltipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["c112_numcgm"]) || $this->c112_numcgm != "")
             $resac = db_query("insert into db_acount values($acount,3627,20203,'".AddSlashes(pg_result($resaco,$conresaco,'c112_numcgm'))."','$this->c112_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["c112_processoadm"]) || $this->c112_processoadm != "")
             $resac = db_query("insert into db_acount values($acount,3627,20204,'".AddSlashes(pg_result($resaco,$conresaco,'c112_processoadm'))."','$this->c112_processoadm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["c112_valor"]) || $this->c112_valor != "")
             $resac = db_query("insert into db_acount values($acount,3627,20205,'".AddSlashes(pg_result($resaco,$conresaco,'c112_valor'))."','$this->c112_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["c112_estornado"]) || $this->c112_estornado != "")
             $resac = db_query("insert into db_acount values($acount,3627,20206,'".AddSlashes(pg_result($resaco,$conresaco,'c112_estornado'))."','$this->c112_estornado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Reconhecimento contabil nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c112_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Reconhecimento contabil nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c112_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c112_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($c112_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($c112_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20201,'$c112_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3627,20201,'','".AddSlashes(pg_result($resaco,$iresaco,'c112_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3627,20202,'','".AddSlashes(pg_result($resaco,$iresaco,'c112_reconhecimentocontabiltipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3627,20203,'','".AddSlashes(pg_result($resaco,$iresaco,'c112_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3627,20204,'','".AddSlashes(pg_result($resaco,$iresaco,'c112_processoadm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3627,20205,'','".AddSlashes(pg_result($resaco,$iresaco,'c112_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3627,20206,'','".AddSlashes(pg_result($resaco,$iresaco,'c112_estornado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from reconhecimentocontabil
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c112_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c112_sequencial = $c112_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Reconhecimento contabil nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c112_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Reconhecimento contabil nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c112_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c112_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:reconhecimentocontabil";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $c112_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from reconhecimentocontabil ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = reconhecimentocontabil.c112_numcgm";
     $sql .= "      inner join reconhecimentocontabiltipo  on  reconhecimentocontabiltipo.c111_sequencial = reconhecimentocontabil.c112_reconhecimentocontabiltipo";
     $sql .= "      inner join conhistdoc  on  conhistdoc.c53_coddoc = reconhecimentocontabiltipo.c111_conhistdoc";
     $sql2 = "";
     if($dbwhere==""){
       if($c112_sequencial!=null ){
         $sql2 .= " where reconhecimentocontabil.c112_sequencial = $c112_sequencial "; 
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
   function sql_query_file ( $c112_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from reconhecimentocontabil ";
     $sql2 = "";
     if($dbwhere==""){
       if($c112_sequencial!=null ){
         $sql2 .= " where reconhecimentocontabil.c112_sequencial = $c112_sequencial "; 
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