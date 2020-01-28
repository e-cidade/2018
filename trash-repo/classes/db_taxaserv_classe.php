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

//MODULO: Cemiterio
//CLASSE DA ENTIDADE taxaserv
class cl_taxaserv { 
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
   var $cm11_i_codigo = 0; 
   var $cm11_c_descr = null; 
   var $cm11_i_receita = 0; 
   var $cm11_i_proced = 0; 
   var $cm11_i_historico = 0; 
   var $cm11_i_tipo = 0; 
   var $cm11_d_datalimite_dia = null; 
   var $cm11_d_datalimite_mes = null; 
   var $cm11_d_datalimite_ano = null; 
   var $cm11_d_datalimite = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 cm11_i_codigo = int4 = Código 
                 cm11_c_descr = char(50) = Descrição 
                 cm11_i_receita = int4 = Receita 
                 cm11_i_proced = int4 = Procedencia 
                 cm11_i_historico = int4 = Histórico 
                 cm11_i_tipo = int4 = Tipo 
                 cm11_d_datalimite = date = Data Limite 
                 ";
   //funcao construtor da classe 
   function cl_taxaserv() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("taxaserv"); 
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
       $this->cm11_i_codigo = ($this->cm11_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm11_i_codigo"]:$this->cm11_i_codigo);
       $this->cm11_c_descr = ($this->cm11_c_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["cm11_c_descr"]:$this->cm11_c_descr);
       $this->cm11_i_receita = ($this->cm11_i_receita == ""?@$GLOBALS["HTTP_POST_VARS"]["cm11_i_receita"]:$this->cm11_i_receita);
       $this->cm11_i_proced = ($this->cm11_i_proced == ""?@$GLOBALS["HTTP_POST_VARS"]["cm11_i_proced"]:$this->cm11_i_proced);
       $this->cm11_i_historico = ($this->cm11_i_historico == ""?@$GLOBALS["HTTP_POST_VARS"]["cm11_i_historico"]:$this->cm11_i_historico);
       $this->cm11_i_tipo = ($this->cm11_i_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm11_i_tipo"]:$this->cm11_i_tipo);
       if($this->cm11_d_datalimite == ""){
         $this->cm11_d_datalimite_dia = ($this->cm11_d_datalimite_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["cm11_d_datalimite_dia"]:$this->cm11_d_datalimite_dia);
         $this->cm11_d_datalimite_mes = ($this->cm11_d_datalimite_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["cm11_d_datalimite_mes"]:$this->cm11_d_datalimite_mes);
         $this->cm11_d_datalimite_ano = ($this->cm11_d_datalimite_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["cm11_d_datalimite_ano"]:$this->cm11_d_datalimite_ano);
         if($this->cm11_d_datalimite_dia != ""){
            $this->cm11_d_datalimite = $this->cm11_d_datalimite_ano."-".$this->cm11_d_datalimite_mes."-".$this->cm11_d_datalimite_dia;
         }
       }
     }else{
       $this->cm11_i_codigo = ($this->cm11_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm11_i_codigo"]:$this->cm11_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($cm11_i_codigo){ 
      $this->atualizacampos();
     if($this->cm11_c_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "cm11_c_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm11_i_receita == null ){ 
       $this->erro_sql = " Campo Receita nao Informado.";
       $this->erro_campo = "cm11_i_receita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm11_i_proced == null ){ 
       $this->erro_sql = " Campo Procedencia nao Informado.";
       $this->erro_campo = "cm11_i_proced";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm11_i_historico == null ){ 
       $this->erro_sql = " Campo Histórico nao Informado.";
       $this->erro_campo = "cm11_i_historico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm11_i_tipo == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "cm11_i_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm11_d_datalimite == null ){ 
       $this->erro_sql = " Campo Data Limite nao Informado.";
       $this->erro_campo = "cm11_d_datalimite_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($cm11_i_codigo == "" || $cm11_i_codigo == null ){
       $result = db_query("select nextval('taxaserv_cm11_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: taxaserv_cm11_i_codigo_seq do campo: cm11_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->cm11_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from taxaserv_cm11_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $cm11_i_codigo)){
         $this->erro_sql = " Campo cm11_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->cm11_i_codigo = $cm11_i_codigo; 
       }
     }
     if(($this->cm11_i_codigo == null) || ($this->cm11_i_codigo == "") ){ 
       $this->erro_sql = " Campo cm11_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into taxaserv(
                                       cm11_i_codigo 
                                      ,cm11_c_descr 
                                      ,cm11_i_receita 
                                      ,cm11_i_proced 
                                      ,cm11_i_historico 
                                      ,cm11_i_tipo 
                                      ,cm11_d_datalimite 
                       )
                values (
                                $this->cm11_i_codigo 
                               ,'$this->cm11_c_descr' 
                               ,$this->cm11_i_receita 
                               ,$this->cm11_i_proced 
                               ,$this->cm11_i_historico 
                               ,$this->cm11_i_tipo 
                               ,".($this->cm11_d_datalimite == "null" || $this->cm11_d_datalimite == ""?"null":"'".$this->cm11_d_datalimite."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "taxaserv ($this->cm11_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "taxaserv já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "taxaserv ($this->cm11_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cm11_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->cm11_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10441,'$this->cm11_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1805,10441,'','".AddSlashes(pg_result($resaco,0,'cm11_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1805,10442,'','".AddSlashes(pg_result($resaco,0,'cm11_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1805,10445,'','".AddSlashes(pg_result($resaco,0,'cm11_i_receita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1805,10446,'','".AddSlashes(pg_result($resaco,0,'cm11_i_proced'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1805,10447,'','".AddSlashes(pg_result($resaco,0,'cm11_i_historico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1805,10448,'','".AddSlashes(pg_result($resaco,0,'cm11_i_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1805,15586,'','".AddSlashes(pg_result($resaco,0,'cm11_d_datalimite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($cm11_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update taxaserv set ";
     $virgula = "";
     if(trim($this->cm11_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm11_i_codigo"])){ 
       $sql  .= $virgula." cm11_i_codigo = $this->cm11_i_codigo ";
       $virgula = ",";
       if(trim($this->cm11_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "cm11_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm11_c_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm11_c_descr"])){ 
       $sql  .= $virgula." cm11_c_descr = '$this->cm11_c_descr' ";
       $virgula = ",";
       if(trim($this->cm11_c_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "cm11_c_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm11_i_receita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm11_i_receita"])){ 
       $sql  .= $virgula." cm11_i_receita = $this->cm11_i_receita ";
       $virgula = ",";
       if(trim($this->cm11_i_receita) == null ){ 
         $this->erro_sql = " Campo Receita nao Informado.";
         $this->erro_campo = "cm11_i_receita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm11_i_proced)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm11_i_proced"])){ 
       $sql  .= $virgula." cm11_i_proced = $this->cm11_i_proced ";
       $virgula = ",";
       if(trim($this->cm11_i_proced) == null ){ 
         $this->erro_sql = " Campo Procedencia nao Informado.";
         $this->erro_campo = "cm11_i_proced";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm11_i_historico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm11_i_historico"])){ 
       $sql  .= $virgula." cm11_i_historico = $this->cm11_i_historico ";
       $virgula = ",";
       if(trim($this->cm11_i_historico) == null ){ 
         $this->erro_sql = " Campo Histórico nao Informado.";
         $this->erro_campo = "cm11_i_historico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm11_i_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm11_i_tipo"])){ 
       $sql  .= $virgula." cm11_i_tipo = $this->cm11_i_tipo ";
       $virgula = ",";
       if(trim($this->cm11_i_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "cm11_i_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm11_d_datalimite)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm11_d_datalimite_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["cm11_d_datalimite_dia"] !="") ){ 
       $sql  .= $virgula." cm11_d_datalimite = '$this->cm11_d_datalimite' ";
       $virgula = ",";
       if(trim($this->cm11_d_datalimite) == null ){ 
         $this->erro_sql = " Campo Data Limite nao Informado.";
         $this->erro_campo = "cm11_d_datalimite_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["cm11_d_datalimite_dia"])){ 
         $sql  .= $virgula." cm11_d_datalimite = null ";
         $virgula = ",";
         if(trim($this->cm11_d_datalimite) == null ){ 
           $this->erro_sql = " Campo Data Limite nao Informado.";
           $this->erro_campo = "cm11_d_datalimite_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($cm11_i_codigo!=null){
       $sql .= " cm11_i_codigo = $this->cm11_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->cm11_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10441,'$this->cm11_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm11_i_codigo"]) || $this->cm11_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,1805,10441,'".AddSlashes(pg_result($resaco,$conresaco,'cm11_i_codigo'))."','$this->cm11_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm11_c_descr"]) || $this->cm11_c_descr != "")
           $resac = db_query("insert into db_acount values($acount,1805,10442,'".AddSlashes(pg_result($resaco,$conresaco,'cm11_c_descr'))."','$this->cm11_c_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm11_i_receita"]) || $this->cm11_i_receita != "")
           $resac = db_query("insert into db_acount values($acount,1805,10445,'".AddSlashes(pg_result($resaco,$conresaco,'cm11_i_receita'))."','$this->cm11_i_receita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm11_i_proced"]) || $this->cm11_i_proced != "")
           $resac = db_query("insert into db_acount values($acount,1805,10446,'".AddSlashes(pg_result($resaco,$conresaco,'cm11_i_proced'))."','$this->cm11_i_proced',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm11_i_historico"]) || $this->cm11_i_historico != "")
           $resac = db_query("insert into db_acount values($acount,1805,10447,'".AddSlashes(pg_result($resaco,$conresaco,'cm11_i_historico'))."','$this->cm11_i_historico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm11_i_tipo"]) || $this->cm11_i_tipo != "")
           $resac = db_query("insert into db_acount values($acount,1805,10448,'".AddSlashes(pg_result($resaco,$conresaco,'cm11_i_tipo'))."','$this->cm11_i_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm11_d_datalimite"]) || $this->cm11_d_datalimite != "")
           $resac = db_query("insert into db_acount values($acount,1805,15586,'".AddSlashes(pg_result($resaco,$conresaco,'cm11_d_datalimite'))."','$this->cm11_d_datalimite',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "taxaserv nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm11_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "taxaserv nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm11_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cm11_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($cm11_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($cm11_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10441,'$cm11_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1805,10441,'','".AddSlashes(pg_result($resaco,$iresaco,'cm11_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1805,10442,'','".AddSlashes(pg_result($resaco,$iresaco,'cm11_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1805,10445,'','".AddSlashes(pg_result($resaco,$iresaco,'cm11_i_receita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1805,10446,'','".AddSlashes(pg_result($resaco,$iresaco,'cm11_i_proced'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1805,10447,'','".AddSlashes(pg_result($resaco,$iresaco,'cm11_i_historico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1805,10448,'','".AddSlashes(pg_result($resaco,$iresaco,'cm11_i_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1805,15586,'','".AddSlashes(pg_result($resaco,$iresaco,'cm11_d_datalimite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from taxaserv
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($cm11_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cm11_i_codigo = $cm11_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "taxaserv nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cm11_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "taxaserv nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cm11_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cm11_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:taxaserv";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $cm11_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from taxaserv ";
     $sql .= "      inner join histcalc  on  histcalc.k01_codigo = taxaserv.cm11_i_historico";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = taxaserv.cm11_i_receita";
     $sql .= "      inner join arretipo  on  arretipo.k00_tipo = taxaserv.cm11_i_tipo";
     $sql .= "      inner join procdiver  on  procdiver.dv09_procdiver = taxaserv.cm11_i_proced";
     $sql .= "      inner join tabrecjm  on  tabrecjm.k02_codjm = tabrec.k02_codjm";
     $sql .= "      inner join cadtipo  on  cadtipo.k03_tipo = arretipo.k03_tipo";
     $sql .= "      inner join histcalc as histcalc2  on  histcalc2.k01_codigo = procdiver.dv09_hist";
     $sql .= "      inner join tabrec   as tabrec2    on  tabrec2.k02_codigo   = procdiver.dv09_receit";
     $sql2 = "";
     if($dbwhere==""){
       if($cm11_i_codigo!=null ){
         $sql2 .= " where taxaserv.cm11_i_codigo = $cm11_i_codigo "; 
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
   function sql_query_file ( $cm11_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from taxaserv ";
     $sql2 = "";
     if($dbwhere==""){
       if($cm11_i_codigo!=null ){
         $sql2 .= " where taxaserv.cm11_i_codigo = $cm11_i_codigo "; 
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