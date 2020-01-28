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

//MODULO: Compras
//CLASSE DA ENTIDADE pctipocompravalores
class cl_pctipocompravalores { 
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
   var $pc85_sequencial = 0; 
   var $pc85_codtipocompra = 0; 
   var $pc85_valorminimo = 0; 
   var $pc85_valormaximo = 0; 
   var $pc85_datainicial_dia = null; 
   var $pc85_datainicial_mes = null; 
   var $pc85_datainicial_ano = null; 
   var $pc85_datainicial = null; 
   var $pc85_datafinal_dia = null; 
   var $pc85_datafinal_mes = null; 
   var $pc85_datafinal_ano = null; 
   var $pc85_datafinal = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc85_sequencial = int4 = Sequencial 
                 pc85_codtipocompra = int4 = Código do Tipo de Compra 
                 pc85_valorminimo = float8 = Valor Minímo 
                 pc85_valormaximo = int4 = Valor Máximo 
                 pc85_datainicial = date = Data Inicial 
                 pc85_datafinal = date = Data Final 
                 ";
   //funcao construtor da classe 
   function cl_pctipocompravalores() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pctipocompravalores"); 
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
       $this->pc85_sequencial = ($this->pc85_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pc85_sequencial"]:$this->pc85_sequencial);
       $this->pc85_codtipocompra = ($this->pc85_codtipocompra == ""?@$GLOBALS["HTTP_POST_VARS"]["pc85_codtipocompra"]:$this->pc85_codtipocompra);
       $this->pc85_valorminimo = ($this->pc85_valorminimo == ""?@$GLOBALS["HTTP_POST_VARS"]["pc85_valorminimo"]:$this->pc85_valorminimo);
       $this->pc85_valormaximo = ($this->pc85_valormaximo == ""?@$GLOBALS["HTTP_POST_VARS"]["pc85_valormaximo"]:$this->pc85_valormaximo);
       if($this->pc85_datainicial == ""){
         $this->pc85_datainicial_dia = ($this->pc85_datainicial_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["pc85_datainicial_dia"]:$this->pc85_datainicial_dia);
         $this->pc85_datainicial_mes = ($this->pc85_datainicial_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["pc85_datainicial_mes"]:$this->pc85_datainicial_mes);
         $this->pc85_datainicial_ano = ($this->pc85_datainicial_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["pc85_datainicial_ano"]:$this->pc85_datainicial_ano);
         if($this->pc85_datainicial_dia != ""){
            $this->pc85_datainicial = $this->pc85_datainicial_ano."-".$this->pc85_datainicial_mes."-".$this->pc85_datainicial_dia;
         }
       }
       if($this->pc85_datafinal == ""){
         $this->pc85_datafinal_dia = ($this->pc85_datafinal_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["pc85_datafinal_dia"]:$this->pc85_datafinal_dia);
         $this->pc85_datafinal_mes = ($this->pc85_datafinal_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["pc85_datafinal_mes"]:$this->pc85_datafinal_mes);
         $this->pc85_datafinal_ano = ($this->pc85_datafinal_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["pc85_datafinal_ano"]:$this->pc85_datafinal_ano);
         if($this->pc85_datafinal_dia != ""){
            $this->pc85_datafinal = $this->pc85_datafinal_ano."-".$this->pc85_datafinal_mes."-".$this->pc85_datafinal_dia;
         }
       }
     }else{
       $this->pc85_sequencial = ($this->pc85_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pc85_sequencial"]:$this->pc85_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($pc85_sequencial){ 
      $this->atualizacampos();
     if($this->pc85_codtipocompra == null ){ 
       $this->erro_sql = " Campo Código do Tipo de Compra nao Informado.";
       $this->erro_campo = "pc85_codtipocompra";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc85_valorminimo == null ){ 
       $this->erro_sql = " Campo Valor Minímo nao Informado.";
       $this->erro_campo = "pc85_valorminimo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc85_valormaximo == null ){ 
       $this->erro_sql = " Campo Valor Máximo nao Informado.";
       $this->erro_campo = "pc85_valormaximo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc85_datainicial == null ){ 
       $this->erro_sql = " Campo Data Inicial nao Informado.";
       $this->erro_campo = "pc85_datainicial_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc85_datafinal == null ){ 
       $this->erro_sql = " Campo Data Final nao Informado.";
       $this->erro_campo = "pc85_datafinal_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($pc85_sequencial == "" || $pc85_sequencial == null ){
       $result = db_query("select nextval('pctipocompravalores_pc85_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: pctipocompravalores_pc85_sequencial_seq do campo: pc85_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->pc85_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from pctipocompravalores_pc85_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc85_sequencial)){
         $this->erro_sql = " Campo pc85_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc85_sequencial = $pc85_sequencial; 
       }
     }
     if(($this->pc85_sequencial == null) || ($this->pc85_sequencial == "") ){ 
       $this->erro_sql = " Campo pc85_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pctipocompravalores(
                                       pc85_sequencial 
                                      ,pc85_codtipocompra 
                                      ,pc85_valorminimo 
                                      ,pc85_valormaximo 
                                      ,pc85_datainicial 
                                      ,pc85_datafinal 
                       )
                values (
                                $this->pc85_sequencial 
                               ,$this->pc85_codtipocompra 
                               ,$this->pc85_valorminimo 
                               ,$this->pc85_valormaximo 
                               ,".($this->pc85_datainicial == "null" || $this->pc85_datainicial == ""?"null":"'".$this->pc85_datainicial."'")." 
                               ,".($this->pc85_datafinal == "null" || $this->pc85_datafinal == ""?"null":"'".$this->pc85_datafinal."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Faixa de Valores do Tipo de Compras ($this->pc85_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Faixa de Valores do Tipo de Compras já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Faixa de Valores do Tipo de Compras ($this->pc85_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc85_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc85_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16678,'$this->pc85_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2933,16678,'','".AddSlashes(pg_result($resaco,0,'pc85_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2933,16679,'','".AddSlashes(pg_result($resaco,0,'pc85_codtipocompra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2933,16680,'','".AddSlashes(pg_result($resaco,0,'pc85_valorminimo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2933,16681,'','".AddSlashes(pg_result($resaco,0,'pc85_valormaximo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2933,16682,'','".AddSlashes(pg_result($resaco,0,'pc85_datainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2933,16683,'','".AddSlashes(pg_result($resaco,0,'pc85_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($pc85_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update pctipocompravalores set ";
     $virgula = "";
     if(trim($this->pc85_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc85_sequencial"])){ 
       $sql  .= $virgula." pc85_sequencial = $this->pc85_sequencial ";
       $virgula = ",";
       if(trim($this->pc85_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "pc85_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc85_codtipocompra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc85_codtipocompra"])){ 
       $sql  .= $virgula." pc85_codtipocompra = $this->pc85_codtipocompra ";
       $virgula = ",";
       if(trim($this->pc85_codtipocompra) == null ){ 
         $this->erro_sql = " Campo Código do Tipo de Compra nao Informado.";
         $this->erro_campo = "pc85_codtipocompra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc85_valorminimo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc85_valorminimo"])){ 
       $sql  .= $virgula." pc85_valorminimo = $this->pc85_valorminimo ";
       $virgula = ",";
       if(trim($this->pc85_valorminimo) == null ){ 
         $this->erro_sql = " Campo Valor Minímo nao Informado.";
         $this->erro_campo = "pc85_valorminimo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc85_valormaximo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc85_valormaximo"])){ 
       $sql  .= $virgula." pc85_valormaximo = $this->pc85_valormaximo ";
       $virgula = ",";
       if(trim($this->pc85_valormaximo) == null ){ 
         $this->erro_sql = " Campo Valor Máximo nao Informado.";
         $this->erro_campo = "pc85_valormaximo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc85_datainicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc85_datainicial_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["pc85_datainicial_dia"] !="") ){ 
       $sql  .= $virgula." pc85_datainicial = '$this->pc85_datainicial' ";
       $virgula = ",";
       if(trim($this->pc85_datainicial) == null ){ 
         $this->erro_sql = " Campo Data Inicial nao Informado.";
         $this->erro_campo = "pc85_datainicial_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["pc85_datainicial_dia"])){ 
         $sql  .= $virgula." pc85_datainicial = null ";
         $virgula = ",";
         if(trim($this->pc85_datainicial) == null ){ 
           $this->erro_sql = " Campo Data Inicial nao Informado.";
           $this->erro_campo = "pc85_datainicial_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->pc85_datafinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc85_datafinal_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["pc85_datafinal_dia"] !="") ){ 
       $sql  .= $virgula." pc85_datafinal = '$this->pc85_datafinal' ";
       $virgula = ",";
       if(trim($this->pc85_datafinal) == null ){ 
         $this->erro_sql = " Campo Data Final nao Informado.";
         $this->erro_campo = "pc85_datafinal_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["pc85_datafinal_dia"])){ 
         $sql  .= $virgula." pc85_datafinal = null ";
         $virgula = ",";
         if(trim($this->pc85_datafinal) == null ){ 
           $this->erro_sql = " Campo Data Final nao Informado.";
           $this->erro_campo = "pc85_datafinal_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($pc85_sequencial!=null){
       $sql .= " pc85_sequencial = $this->pc85_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc85_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16678,'$this->pc85_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc85_sequencial"]) || $this->pc85_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2933,16678,'".AddSlashes(pg_result($resaco,$conresaco,'pc85_sequencial'))."','$this->pc85_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc85_codtipocompra"]) || $this->pc85_codtipocompra != "")
           $resac = db_query("insert into db_acount values($acount,2933,16679,'".AddSlashes(pg_result($resaco,$conresaco,'pc85_codtipocompra'))."','$this->pc85_codtipocompra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc85_valorminimo"]) || $this->pc85_valorminimo != "")
           $resac = db_query("insert into db_acount values($acount,2933,16680,'".AddSlashes(pg_result($resaco,$conresaco,'pc85_valorminimo'))."','$this->pc85_valorminimo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc85_valormaximo"]) || $this->pc85_valormaximo != "")
           $resac = db_query("insert into db_acount values($acount,2933,16681,'".AddSlashes(pg_result($resaco,$conresaco,'pc85_valormaximo'))."','$this->pc85_valormaximo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc85_datainicial"]) || $this->pc85_datainicial != "")
           $resac = db_query("insert into db_acount values($acount,2933,16682,'".AddSlashes(pg_result($resaco,$conresaco,'pc85_datainicial'))."','$this->pc85_datainicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc85_datafinal"]) || $this->pc85_datafinal != "")
           $resac = db_query("insert into db_acount values($acount,2933,16683,'".AddSlashes(pg_result($resaco,$conresaco,'pc85_datafinal'))."','$this->pc85_datafinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Faixa de Valores do Tipo de Compras nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc85_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Faixa de Valores do Tipo de Compras nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc85_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc85_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($pc85_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc85_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16678,'$pc85_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2933,16678,'','".AddSlashes(pg_result($resaco,$iresaco,'pc85_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2933,16679,'','".AddSlashes(pg_result($resaco,$iresaco,'pc85_codtipocompra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2933,16680,'','".AddSlashes(pg_result($resaco,$iresaco,'pc85_valorminimo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2933,16681,'','".AddSlashes(pg_result($resaco,$iresaco,'pc85_valormaximo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2933,16682,'','".AddSlashes(pg_result($resaco,$iresaco,'pc85_datainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2933,16683,'','".AddSlashes(pg_result($resaco,$iresaco,'pc85_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pctipocompravalores
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc85_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc85_sequencial = $pc85_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Faixa de Valores do Tipo de Compras nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc85_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Faixa de Valores do Tipo de Compras nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc85_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc85_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:pctipocompravalores";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $pc85_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pctipocompravalores ";
     $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = pctipocompravalores.pc85_codtipocompra";
     $sql2 = "";
     if($dbwhere==""){
       if($pc85_sequencial!=null ){
         $sql2 .= " where pctipocompravalores.pc85_sequencial = $pc85_sequencial "; 
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
   function sql_query_file ( $pc85_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pctipocompravalores ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc85_sequencial!=null ){
         $sql2 .= " where pctipocompravalores.pc85_sequencial = $pc85_sequencial "; 
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