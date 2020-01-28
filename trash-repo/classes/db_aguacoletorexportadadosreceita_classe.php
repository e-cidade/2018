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

//MODULO: Agua
//CLASSE DA ENTIDADE aguacoletorexportadadosreceita
class cl_aguacoletorexportadadosreceita { 
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
   var $x52_sequencial = 0; 
   var $x52_receita = 0; 
   var $x52_aguacoletorexportadados = 0; 
   var $x52_descricao = null; 
   var $x52_numpar = null; 
   var $x52_valor = 0; 
   var $x52_numpre = 0; 
   var $x52_numtot = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 x52_sequencial = int4 = Código 
                 x52_receita = int4 = Receita 
                 x52_aguacoletorexportadados = int4 = Código Exportação Dados 
                 x52_descricao = varchar(17) = Descricao da Receita 
                 x52_numpar = varchar(9) = Numero Parcela 
                 x52_valor = float8 = Valor 
                 x52_numpre = int4 = Número Arrecadação 
                 x52_numtot = int4 = Número Total de Parcelas 
                 ";
   //funcao construtor da classe 
   function cl_aguacoletorexportadadosreceita() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("aguacoletorexportadadosreceita"); 
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
       $this->x52_sequencial = ($this->x52_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["x52_sequencial"]:$this->x52_sequencial);
       $this->x52_receita = ($this->x52_receita == ""?@$GLOBALS["HTTP_POST_VARS"]["x52_receita"]:$this->x52_receita);
       $this->x52_aguacoletorexportadados = ($this->x52_aguacoletorexportadados == ""?@$GLOBALS["HTTP_POST_VARS"]["x52_aguacoletorexportadados"]:$this->x52_aguacoletorexportadados);
       $this->x52_descricao = ($this->x52_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["x52_descricao"]:$this->x52_descricao);
       $this->x52_numpar = ($this->x52_numpar == ""?@$GLOBALS["HTTP_POST_VARS"]["x52_numpar"]:$this->x52_numpar);
       $this->x52_valor = ($this->x52_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["x52_valor"]:$this->x52_valor);
       $this->x52_numpre = ($this->x52_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["x52_numpre"]:$this->x52_numpre);
       $this->x52_numtot = ($this->x52_numtot == ""?@$GLOBALS["HTTP_POST_VARS"]["x52_numtot"]:$this->x52_numtot);
     }else{
       $this->x52_sequencial = ($this->x52_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["x52_sequencial"]:$this->x52_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($x52_sequencial){ 
      $this->atualizacampos();
     if($this->x52_receita == null ){ 
       $this->erro_sql = " Campo Receita nao Informado.";
       $this->erro_campo = "x52_receita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x52_aguacoletorexportadados == null ){ 
       $this->erro_sql = " Campo Código Exportação Dados nao Informado.";
       $this->erro_campo = "x52_aguacoletorexportadados";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x52_descricao == null ){ 
       $this->erro_sql = " Campo Descricao da Receita nao Informado.";
       $this->erro_campo = "x52_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x52_numpar == null ){ 
       $this->erro_sql = " Campo Numero Parcela nao Informado.";
       $this->erro_campo = "x52_numpar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x52_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "x52_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x52_numpre == null ){ 
       $this->erro_sql = " Campo Número Arrecadação nao Informado.";
       $this->erro_campo = "x52_numpre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x52_numtot == null ){ 
       $this->erro_sql = " Campo Número Total de Parcelas nao Informado.";
       $this->erro_campo = "x52_numtot";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($x52_sequencial == "" || $x52_sequencial == null ){
       $result = db_query("select nextval('aguacoletorexportadadosreceita_x52_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: aguacoletorexportadadosreceita_x52_sequencial_seq do campo: x52_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->x52_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from aguacoletorexportadadosreceita_x52_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $x52_sequencial)){
         $this->erro_sql = " Campo x52_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->x52_sequencial = $x52_sequencial; 
       }
     }
     if(($this->x52_sequencial == null) || ($this->x52_sequencial == "") ){ 
       $this->erro_sql = " Campo x52_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into aguacoletorexportadadosreceita(
                                       x52_sequencial 
                                      ,x52_receita 
                                      ,x52_aguacoletorexportadados 
                                      ,x52_descricao 
                                      ,x52_numpar 
                                      ,x52_valor 
                                      ,x52_numpre 
                                      ,x52_numtot 
                       )
                values (
                                $this->x52_sequencial 
                               ,$this->x52_receita 
                               ,$this->x52_aguacoletorexportadados 
                               ,'$this->x52_descricao' 
                               ,'$this->x52_numpar' 
                               ,$this->x52_valor 
                               ,$this->x52_numpre 
                               ,$this->x52_numtot 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Agua Coletor Exporta Dados Receita ($this->x52_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Agua Coletor Exporta Dados Receita já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Agua Coletor Exporta Dados Receita ($this->x52_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x52_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->x52_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15397,'$this->x52_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2704,15397,'','".AddSlashes(pg_result($resaco,0,'x52_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2704,15398,'','".AddSlashes(pg_result($resaco,0,'x52_receita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2704,15399,'','".AddSlashes(pg_result($resaco,0,'x52_aguacoletorexportadados'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2704,15400,'','".AddSlashes(pg_result($resaco,0,'x52_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2704,15401,'','".AddSlashes(pg_result($resaco,0,'x52_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2704,15402,'','".AddSlashes(pg_result($resaco,0,'x52_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2704,15404,'','".AddSlashes(pg_result($resaco,0,'x52_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2704,15405,'','".AddSlashes(pg_result($resaco,0,'x52_numtot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($x52_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update aguacoletorexportadadosreceita set ";
     $virgula = "";
     if(trim($this->x52_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x52_sequencial"])){ 
       $sql  .= $virgula." x52_sequencial = $this->x52_sequencial ";
       $virgula = ",";
       if(trim($this->x52_sequencial) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "x52_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x52_receita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x52_receita"])){ 
       $sql  .= $virgula." x52_receita = $this->x52_receita ";
       $virgula = ",";
       if(trim($this->x52_receita) == null ){ 
         $this->erro_sql = " Campo Receita nao Informado.";
         $this->erro_campo = "x52_receita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x52_aguacoletorexportadados)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x52_aguacoletorexportadados"])){ 
       $sql  .= $virgula." x52_aguacoletorexportadados = $this->x52_aguacoletorexportadados ";
       $virgula = ",";
       if(trim($this->x52_aguacoletorexportadados) == null ){ 
         $this->erro_sql = " Campo Código Exportação Dados nao Informado.";
         $this->erro_campo = "x52_aguacoletorexportadados";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x52_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x52_descricao"])){ 
       $sql  .= $virgula." x52_descricao = '$this->x52_descricao' ";
       $virgula = ",";
       if(trim($this->x52_descricao) == null ){ 
         $this->erro_sql = " Campo Descricao da Receita nao Informado.";
         $this->erro_campo = "x52_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x52_numpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x52_numpar"])){ 
       $sql  .= $virgula." x52_numpar = '$this->x52_numpar' ";
       $virgula = ",";
       if(trim($this->x52_numpar) == null ){ 
         $this->erro_sql = " Campo Numero Parcela nao Informado.";
         $this->erro_campo = "x52_numpar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x52_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x52_valor"])){ 
       $sql  .= $virgula." x52_valor = $this->x52_valor ";
       $virgula = ",";
       if(trim($this->x52_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "x52_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x52_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x52_numpre"])){ 
       $sql  .= $virgula." x52_numpre = $this->x52_numpre ";
       $virgula = ",";
       if(trim($this->x52_numpre) == null ){ 
         $this->erro_sql = " Campo Número Arrecadação nao Informado.";
         $this->erro_campo = "x52_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x52_numtot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x52_numtot"])){ 
       $sql  .= $virgula." x52_numtot = $this->x52_numtot ";
       $virgula = ",";
       if(trim($this->x52_numtot) == null ){ 
         $this->erro_sql = " Campo Número Total de Parcelas nao Informado.";
         $this->erro_campo = "x52_numtot";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($x52_sequencial!=null){
       $sql .= " x52_sequencial = $this->x52_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->x52_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15397,'$this->x52_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x52_sequencial"]) || $this->x52_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2704,15397,'".AddSlashes(pg_result($resaco,$conresaco,'x52_sequencial'))."','$this->x52_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x52_receita"]) || $this->x52_receita != "")
           $resac = db_query("insert into db_acount values($acount,2704,15398,'".AddSlashes(pg_result($resaco,$conresaco,'x52_receita'))."','$this->x52_receita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x52_aguacoletorexportadados"]) || $this->x52_aguacoletorexportadados != "")
           $resac = db_query("insert into db_acount values($acount,2704,15399,'".AddSlashes(pg_result($resaco,$conresaco,'x52_aguacoletorexportadados'))."','$this->x52_aguacoletorexportadados',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x52_descricao"]) || $this->x52_descricao != "")
           $resac = db_query("insert into db_acount values($acount,2704,15400,'".AddSlashes(pg_result($resaco,$conresaco,'x52_descricao'))."','$this->x52_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x52_numpar"]) || $this->x52_numpar != "")
           $resac = db_query("insert into db_acount values($acount,2704,15401,'".AddSlashes(pg_result($resaco,$conresaco,'x52_numpar'))."','$this->x52_numpar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x52_valor"]) || $this->x52_valor != "")
           $resac = db_query("insert into db_acount values($acount,2704,15402,'".AddSlashes(pg_result($resaco,$conresaco,'x52_valor'))."','$this->x52_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x52_numpre"]) || $this->x52_numpre != "")
           $resac = db_query("insert into db_acount values($acount,2704,15404,'".AddSlashes(pg_result($resaco,$conresaco,'x52_numpre'))."','$this->x52_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x52_numtot"]) || $this->x52_numtot != "")
           $resac = db_query("insert into db_acount values($acount,2704,15405,'".AddSlashes(pg_result($resaco,$conresaco,'x52_numtot'))."','$this->x52_numtot',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Agua Coletor Exporta Dados Receita nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->x52_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Agua Coletor Exporta Dados Receita nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->x52_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x52_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($x52_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($x52_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15397,'$x52_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2704,15397,'','".AddSlashes(pg_result($resaco,$iresaco,'x52_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2704,15398,'','".AddSlashes(pg_result($resaco,$iresaco,'x52_receita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2704,15399,'','".AddSlashes(pg_result($resaco,$iresaco,'x52_aguacoletorexportadados'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2704,15400,'','".AddSlashes(pg_result($resaco,$iresaco,'x52_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2704,15401,'','".AddSlashes(pg_result($resaco,$iresaco,'x52_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2704,15402,'','".AddSlashes(pg_result($resaco,$iresaco,'x52_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2704,15404,'','".AddSlashes(pg_result($resaco,$iresaco,'x52_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2704,15405,'','".AddSlashes(pg_result($resaco,$iresaco,'x52_numtot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from aguacoletorexportadadosreceita
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($x52_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " x52_sequencial = $x52_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Agua Coletor Exporta Dados Receita nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$x52_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Agua Coletor Exporta Dados Receita nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$x52_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$x52_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:aguacoletorexportadadosreceita";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $x52_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguacoletorexportadadosreceita ";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = aguacoletorexportadadosreceita.x52_receita";
     $sql .= "      inner join aguacoletorexportadados  on  aguacoletorexportadados.x50_sequencial = aguacoletorexportadadosreceita.x52_aguacoletorexportadados";
     $sql .= "      inner join tabrecjm  on  tabrecjm.k02_codjm = tabrec.k02_codjm";
     $sql .= "      inner join tabrectipo  on  tabrectipo.k116_sequencial = tabrec.k02_tabrectipo";
     $sql .= "      inner join bairro  on  bairro.j13_codi = aguacoletorexportadados.x50_codbairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = aguacoletorexportadados.x50_codlogradouro";
     $sql .= "      left  join zonas  on  zonas.j50_zona = aguacoletorexportadados.x50_zona";
     $sql .= "      inner join aguahidromatric  on  aguahidromatric.x04_codhidrometro = aguacoletorexportadados.x50_codhidrometro";
     $sql .= "      inner join aguabase  on  aguabase.x01_matric = aguacoletorexportadados.x50_matric";
     $sql .= "      inner join aguarota  on  aguarota.x06_codrota = aguacoletorexportadados.x50_rota";
     $sql .= "      left  join ruastipo  on  ruastipo.j88_codigo = aguacoletorexportadados.x50_tipo";
     $sql .= "      inner join aguacoletorexporta  as a on   a.x49_sequencial = aguacoletorexportadados.x50_aguacoletorexporta";
     $sql .= "      left  join aguacoletorexportadados  as b on   b.x50_sequencial = aguacoletorexportadados.x50_aguacoletorexportadados";
     $sql2 = "";
     if($dbwhere==""){
       if($x52_sequencial!=null ){
         $sql2 .= " where aguacoletorexportadadosreceita.x52_sequencial = $x52_sequencial "; 
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
   function sql_query_file ( $x52_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguacoletorexportadadosreceita ";
     $sql2 = "";
     if($dbwhere==""){
       if($x52_sequencial!=null ){
         $sql2 .= " where aguacoletorexportadadosreceita.x52_sequencial = $x52_sequencial "; 
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
   function sql_query_dados ( $x52_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguacoletorexportadadosreceita ";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = aguacoletorexportadadosreceita.x52_receita";
     $sql .= "      inner join aguacoletorexportadados  on  aguacoletorexportadados.x50_sequencial = aguacoletorexportadadosreceita.x52_aguacoletorexportadados";
     $sql .= "      inner join tabrecjm  on  tabrecjm.k02_codjm = tabrec.k02_codjm";
     $sql .= "      inner join tabrectipo  on  tabrectipo.k116_sequencial = tabrec.k02_tabrectipo";
     $sql .= "      inner join bairro  on  bairro.j13_codi = aguacoletorexportadados.x50_codbairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = aguacoletorexportadados.x50_codlogradouro";
     $sql .= "      left  join zonas  on  zonas.j50_zona = aguacoletorexportadados.x50_zona";
     $sql .= "      inner join aguahidromatric  on  aguahidromatric.x04_codhidrometro = aguacoletorexportadados.x50_codhidrometro";
     $sql .= "      inner join aguabase  on  aguabase.x01_matric = aguacoletorexportadados.x50_matric";
     $sql .= "      inner join aguarota  on  aguarota.x06_codrota = aguacoletorexportadados.x50_rota";
     $sql .= "      left  join ruastipo  on  ruastipo.j88_codigo = aguacoletorexportadados.x50_tipo";
     $sql .= "      inner join aguacoletorexporta  as a on   a.x49_sequencial = aguacoletorexportadados.x50_aguacoletorexporta";
     
     $sql2 = "";
     if($dbwhere==""){
       if($x52_sequencial!=null ){
         $sql2 .= " where aguacoletorexportadadosreceita.x52_sequencial = $x52_sequencial "; 
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