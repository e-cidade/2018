<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//MODULO: issqn
//CLASSE DA ENTIDADE tabativportetipcalc
class cl_tabativportetipcalc { 
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
   var $q143_sequencial = 0; 
   var $q143_ativid = 0; 
   var $q143_issporte = 0; 
   var $q143_tipcalc = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q143_sequencial = int4 = Código 
                 q143_ativid = int4 = Codigo da Atividade 
                 q143_issporte = int8 = Cod. Porte 
                 q143_tipcalc = int4 = Código do Tipo de Cálculo 
                 ";
   //funcao construtor da classe 
   function cl_tabativportetipcalc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tabativportetipcalc"); 
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
       $this->q143_sequencial = ($this->q143_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q143_sequencial"]:$this->q143_sequencial);
       $this->q143_ativid = ($this->q143_ativid == ""?@$GLOBALS["HTTP_POST_VARS"]["q143_ativid"]:$this->q143_ativid);
       $this->q143_issporte = ($this->q143_issporte == ""?@$GLOBALS["HTTP_POST_VARS"]["q143_issporte"]:$this->q143_issporte);
       $this->q143_tipcalc = ($this->q143_tipcalc == ""?@$GLOBALS["HTTP_POST_VARS"]["q143_tipcalc"]:$this->q143_tipcalc);
     }else{
       $this->q143_sequencial = ($this->q143_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q143_sequencial"]:$this->q143_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q143_sequencial){ 
      $this->atualizacampos();
     if($this->q143_ativid == null ){ 
       $this->erro_sql = " Campo Codigo da Atividade não informado.";
       $this->erro_campo = "q143_ativid";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q143_issporte == null ){ 
       $this->erro_sql = " Campo Cod. Porte não informado.";
       $this->erro_campo = "q143_issporte";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q143_tipcalc == null ){ 
       $this->erro_sql = " Campo Código do Tipo de Cálculo não informado.";
       $this->erro_campo = "q143_tipcalc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q143_sequencial == "" || $q143_sequencial == null ){
       $result = db_query("select nextval('tabativportetipcalc_q143_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tabativportetipcalc_q143_sequencial_seq do campo: q143_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q143_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tabativportetipcalc_q143_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q143_sequencial)){
         $this->erro_sql = " Campo q143_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q143_sequencial = $q143_sequencial; 
       }
     }
     if(($this->q143_sequencial == null) || ($this->q143_sequencial == "") ){ 
       $this->erro_sql = " Campo q143_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tabativportetipcalc(
                                       q143_sequencial 
                                      ,q143_ativid 
                                      ,q143_issporte 
                                      ,q143_tipcalc 
                       )
                values (
                                $this->q143_sequencial 
                               ,$this->q143_ativid 
                               ,$this->q143_issporte 
                               ,$this->q143_tipcalc 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tipo de Cálculo por Porte/Atividade ($this->q143_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tipo de Cálculo por Porte/Atividade já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tipo de Cálculo por Porte/Atividade ($this->q143_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q143_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->q143_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20475,'$this->q143_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3682,20475,'','".AddSlashes(pg_result($resaco,0,'q143_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3682,20476,'','".AddSlashes(pg_result($resaco,0,'q143_ativid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3682,20477,'','".AddSlashes(pg_result($resaco,0,'q143_issporte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3682,20478,'','".AddSlashes(pg_result($resaco,0,'q143_tipcalc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q143_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update tabativportetipcalc set ";
     $virgula = "";
     if(trim($this->q143_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q143_sequencial"])){ 
       $sql  .= $virgula." q143_sequencial = $this->q143_sequencial ";
       $virgula = ",";
       if(trim($this->q143_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "q143_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q143_ativid)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q143_ativid"])){ 
       $sql  .= $virgula." q143_ativid = $this->q143_ativid ";
       $virgula = ",";
       if(trim($this->q143_ativid) == null ){ 
         $this->erro_sql = " Campo Codigo da Atividade não informado.";
         $this->erro_campo = "q143_ativid";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q143_issporte)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q143_issporte"])){ 
       $sql  .= $virgula." q143_issporte = $this->q143_issporte ";
       $virgula = ",";
       if(trim($this->q143_issporte) == null ){ 
         $this->erro_sql = " Campo Cod. Porte não informado.";
         $this->erro_campo = "q143_issporte";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q143_tipcalc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q143_tipcalc"])){ 
       $sql  .= $virgula." q143_tipcalc = $this->q143_tipcalc ";
       $virgula = ",";
       if(trim($this->q143_tipcalc) == null ){ 
         $this->erro_sql = " Campo Código do Tipo de Cálculo não informado.";
         $this->erro_campo = "q143_tipcalc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q143_sequencial!=null){
       $sql .= " q143_sequencial = $this->q143_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->q143_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20475,'$this->q143_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["q143_sequencial"]) || $this->q143_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3682,20475,'".AddSlashes(pg_result($resaco,$conresaco,'q143_sequencial'))."','$this->q143_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["q143_ativid"]) || $this->q143_ativid != "")
             $resac = db_query("insert into db_acount values($acount,3682,20476,'".AddSlashes(pg_result($resaco,$conresaco,'q143_ativid'))."','$this->q143_ativid',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["q143_issporte"]) || $this->q143_issporte != "")
             $resac = db_query("insert into db_acount values($acount,3682,20477,'".AddSlashes(pg_result($resaco,$conresaco,'q143_issporte'))."','$this->q143_issporte',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["q143_tipcalc"]) || $this->q143_tipcalc != "")
             $resac = db_query("insert into db_acount values($acount,3682,20478,'".AddSlashes(pg_result($resaco,$conresaco,'q143_tipcalc'))."','$this->q143_tipcalc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipo de Cálculo por Porte/Atividade nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q143_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipo de Cálculo por Porte/Atividade nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q143_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q143_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q143_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($q143_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20475,'$q143_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3682,20475,'','".AddSlashes(pg_result($resaco,$iresaco,'q143_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3682,20476,'','".AddSlashes(pg_result($resaco,$iresaco,'q143_ativid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3682,20477,'','".AddSlashes(pg_result($resaco,$iresaco,'q143_issporte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3682,20478,'','".AddSlashes(pg_result($resaco,$iresaco,'q143_tipcalc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from tabativportetipcalc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q143_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q143_sequencial = $q143_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipo de Cálculo por Porte/Atividade nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q143_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipo de Cálculo por Porte/Atividade nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q143_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q143_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:tabativportetipcalc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $q143_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tabativportetipcalc ";
     $sql .= "      inner join ativid  on  ativid.q03_ativ = tabativportetipcalc.q143_ativid";
     $sql .= "      inner join tipcalc  on  tipcalc.q81_codigo = tabativportetipcalc.q143_tipcalc";
     $sql .= "      inner join issporte  on  issporte.q40_codporte = tabativportetipcalc.q143_issporte";
     $sql .= "      inner join cadcalc  on  cadcalc.q85_codigo = tipcalc.q81_cadcalc";
     $sql .= "      inner join geradesc  on  geradesc.q89_codigo = tipcalc.q81_gera";
     $sql2 = "";
     if($dbwhere==""){
       if($q143_sequencial!=null ){
         $sql2 .= " where tabativportetipcalc.q143_sequencial = $q143_sequencial "; 
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
   function sql_query_file ( $q143_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tabativportetipcalc ";
     $sql2 = "";
     if($dbwhere==""){
       if($q143_sequencial!=null ){
         $sql2 .= " where tabativportetipcalc.q143_sequencial = $q143_sequencial "; 
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