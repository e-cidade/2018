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

//MODULO: material
//CLASSE DA ENTIDADE posicaoestoque
class cl_posicaoestoque { 
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
   var $m06_sequencial = 0; 
   var $m06_posicaoestoqueprocessamento = 0; 
   var $m06_matestoque = 0; 
   var $m06_quantidade = 0; 
   var $m06_valor = 0; 
   var $m06_precomedio = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m06_sequencial = int4 = Código Sequencial 
                 m06_posicaoestoqueprocessamento = int4 = Código do Processamento 
                 m06_matestoque = int4 = Código do Estoque 
                 m06_quantidade = numeric(10) = Quantidade 
                 m06_valor = numeric(10) = Valor Total 
                 m06_precomedio = numeric(10) = Preço Médio 
                 ";
   //funcao construtor da classe 
   function cl_posicaoestoque() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("posicaoestoque"); 
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
       $this->m06_sequencial = ($this->m06_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["m06_sequencial"]:$this->m06_sequencial);
       $this->m06_posicaoestoqueprocessamento = ($this->m06_posicaoestoqueprocessamento == ""?@$GLOBALS["HTTP_POST_VARS"]["m06_posicaoestoqueprocessamento"]:$this->m06_posicaoestoqueprocessamento);
       $this->m06_matestoque = ($this->m06_matestoque == ""?@$GLOBALS["HTTP_POST_VARS"]["m06_matestoque"]:$this->m06_matestoque);
       $this->m06_quantidade = ($this->m06_quantidade == ""?@$GLOBALS["HTTP_POST_VARS"]["m06_quantidade"]:$this->m06_quantidade);
       $this->m06_valor = ($this->m06_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["m06_valor"]:$this->m06_valor);
       $this->m06_precomedio = ($this->m06_precomedio == ""?@$GLOBALS["HTTP_POST_VARS"]["m06_precomedio"]:$this->m06_precomedio);
     }else{
       $this->m06_sequencial = ($this->m06_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["m06_sequencial"]:$this->m06_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($m06_sequencial){ 
      $this->atualizacampos();
     if($this->m06_posicaoestoqueprocessamento == null ){ 
       $this->erro_sql = " Campo Código do Processamento não informado.";
       $this->erro_campo = "m06_posicaoestoqueprocessamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m06_matestoque == null ){ 
       $this->erro_sql = " Campo Código do Estoque não informado.";
       $this->erro_campo = "m06_matestoque";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m06_quantidade == null ){ 
       $this->erro_sql = " Campo Quantidade não informado.";
       $this->erro_campo = "m06_quantidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m06_valor == null ){ 
       $this->erro_sql = " Campo Valor Total não informado.";
       $this->erro_campo = "m06_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m06_precomedio == null ){ 
       $this->erro_sql = " Campo Preço Médio não informado.";
       $this->erro_campo = "m06_precomedio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($m06_sequencial == "" || $m06_sequencial == null ){
       $result = db_query("select nextval('posicaoestoque_m06_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: posicaoestoque_m06_sequencial_seq do campo: m06_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->m06_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from posicaoestoque_m06_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $m06_sequencial)){
         $this->erro_sql = " Campo m06_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->m06_sequencial = $m06_sequencial; 
       }
     }
     if(($this->m06_sequencial == null) || ($this->m06_sequencial == "") ){ 
       $this->erro_sql = " Campo m06_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into posicaoestoque(
                                       m06_sequencial 
                                      ,m06_posicaoestoqueprocessamento 
                                      ,m06_matestoque 
                                      ,m06_quantidade 
                                      ,m06_valor 
                                      ,m06_precomedio 
                       )
                values (
                                $this->m06_sequencial 
                               ,$this->m06_posicaoestoqueprocessamento 
                               ,$this->m06_matestoque 
                               ,$this->m06_quantidade 
                               ,$this->m06_valor 
                               ,$this->m06_precomedio 
                      )";

     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "posicaoestoque ($this->m06_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "posicaoestoque já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "posicaoestoque ($this->m06_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m06_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->m06_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20392,'$this->m06_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3665,20392,'','".AddSlashes(pg_result($resaco,0,'m06_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3665,20400,'','".AddSlashes(pg_result($resaco,0,'m06_posicaoestoqueprocessamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3665,20393,'','".AddSlashes(pg_result($resaco,0,'m06_matestoque'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3665,20394,'','".AddSlashes(pg_result($resaco,0,'m06_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3665,20395,'','".AddSlashes(pg_result($resaco,0,'m06_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3665,20396,'','".AddSlashes(pg_result($resaco,0,'m06_precomedio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m06_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update posicaoestoque set ";
     $virgula = "";
     if(trim($this->m06_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m06_sequencial"])){ 
       $sql  .= $virgula." m06_sequencial = $this->m06_sequencial ";
       $virgula = ",";
       if(trim($this->m06_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial não informado.";
         $this->erro_campo = "m06_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m06_posicaoestoqueprocessamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m06_posicaoestoqueprocessamento"])){ 
       $sql  .= $virgula." m06_posicaoestoqueprocessamento = $this->m06_posicaoestoqueprocessamento ";
       $virgula = ",";
       if(trim($this->m06_posicaoestoqueprocessamento) == null ){ 
         $this->erro_sql = " Campo Código do Processamento não informado.";
         $this->erro_campo = "m06_posicaoestoqueprocessamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m06_matestoque)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m06_matestoque"])){ 
       $sql  .= $virgula." m06_matestoque = $this->m06_matestoque ";
       $virgula = ",";
       if(trim($this->m06_matestoque) == null ){ 
         $this->erro_sql = " Campo Código do Estoque não informado.";
         $this->erro_campo = "m06_matestoque";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m06_quantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m06_quantidade"])){ 
       $sql  .= $virgula." m06_quantidade = $this->m06_quantidade ";
       $virgula = ",";
       if(trim($this->m06_quantidade) == null ){ 
         $this->erro_sql = " Campo Quantidade não informado.";
         $this->erro_campo = "m06_quantidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m06_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m06_valor"])){ 
       $sql  .= $virgula." m06_valor = $this->m06_valor ";
       $virgula = ",";
       if(trim($this->m06_valor) == null ){ 
         $this->erro_sql = " Campo Valor Total não informado.";
         $this->erro_campo = "m06_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m06_precomedio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m06_precomedio"])){ 
       $sql  .= $virgula." m06_precomedio = $this->m06_precomedio ";
       $virgula = ",";
       if(trim($this->m06_precomedio) == null ){ 
         $this->erro_sql = " Campo Preço Médio não informado.";
         $this->erro_campo = "m06_precomedio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($m06_sequencial!=null){
       $sql .= " m06_sequencial = $this->m06_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->m06_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20392,'$this->m06_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["m06_sequencial"]) || $this->m06_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3665,20392,'".AddSlashes(pg_result($resaco,$conresaco,'m06_sequencial'))."','$this->m06_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["m06_posicaoestoqueprocessamento"]) || $this->m06_posicaoestoqueprocessamento != "")
             $resac = db_query("insert into db_acount values($acount,3665,20400,'".AddSlashes(pg_result($resaco,$conresaco,'m06_posicaoestoqueprocessamento'))."','$this->m06_posicaoestoqueprocessamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["m06_matestoque"]) || $this->m06_matestoque != "")
             $resac = db_query("insert into db_acount values($acount,3665,20393,'".AddSlashes(pg_result($resaco,$conresaco,'m06_matestoque'))."','$this->m06_matestoque',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["m06_quantidade"]) || $this->m06_quantidade != "")
             $resac = db_query("insert into db_acount values($acount,3665,20394,'".AddSlashes(pg_result($resaco,$conresaco,'m06_quantidade'))."','$this->m06_quantidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["m06_valor"]) || $this->m06_valor != "")
             $resac = db_query("insert into db_acount values($acount,3665,20395,'".AddSlashes(pg_result($resaco,$conresaco,'m06_valor'))."','$this->m06_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["m06_precomedio"]) || $this->m06_precomedio != "")
             $resac = db_query("insert into db_acount values($acount,3665,20396,'".AddSlashes(pg_result($resaco,$conresaco,'m06_precomedio'))."','$this->m06_precomedio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "posicaoestoque nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m06_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "posicaoestoque nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m06_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m06_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m06_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($m06_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20392,'$m06_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3665,20392,'','".AddSlashes(pg_result($resaco,$iresaco,'m06_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3665,20400,'','".AddSlashes(pg_result($resaco,$iresaco,'m06_posicaoestoqueprocessamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3665,20393,'','".AddSlashes(pg_result($resaco,$iresaco,'m06_matestoque'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3665,20394,'','".AddSlashes(pg_result($resaco,$iresaco,'m06_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3665,20395,'','".AddSlashes(pg_result($resaco,$iresaco,'m06_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3665,20396,'','".AddSlashes(pg_result($resaco,$iresaco,'m06_precomedio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from posicaoestoque
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m06_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m06_sequencial = $m06_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "posicaoestoque nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m06_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "posicaoestoque nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m06_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m06_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:posicaoestoque";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $m06_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from posicaoestoque ";
     $sql .= "      inner join matestoque  on  matestoque.m70_codigo = posicaoestoque.m06_matestoque";
     $sql .= "      inner join posicaoestoqueprocessamento  on  posicaoestoqueprocessamento.m05_sequencial = posicaoestoque.m06_posicaoestoqueprocessamento";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = matestoque.m70_coddepto";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = matestoque.m70_codmatmater";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = posicaoestoqueprocessamento.m05_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($m06_sequencial!=null ){
         $sql2 .= " where posicaoestoque.m06_sequencial = $m06_sequencial "; 
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
   function sql_query_file ( $m06_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from posicaoestoque ";
     $sql2 = "";
     if($dbwhere==""){
       if($m06_sequencial!=null ){
         $sql2 .= " where posicaoestoque.m06_sequencial = $m06_sequencial "; 
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

  function sql_query_estoque ( $m06_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){

    $sql = "select ";
    if($campos != "*" ){
      $campos_sql = explode("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }else{
      $sql .= $campos;
    }
    $sql .= " from posicaoestoque ";
    $sql .= "      inner join matestoque  on  matestoque.m70_codigo = posicaoestoque.m06_matestoque";
    $sql .= "      inner join posicaoestoqueprocessamento  on  posicaoestoqueprocessamento.m05_sequencial = posicaoestoque.m06_posicaoestoqueprocessamento";
    $sql2 = "";
    if($dbwhere==""){
      if($m06_sequencial!=null ){
        $sql2 .= " where posicaoestoque.m06_sequencial = $m06_sequencial ";
      }
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if($ordem != null ){
      $sql .= " order by ";
      $campos_sql = explode("#",$ordem);
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