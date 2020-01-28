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

//MODULO: prefeitura
//CLASSE DA ENTIDADE listainscrcab
class cl_listainscrcab { 
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
   var $p11_codigo = 0; 
   var $p11_numcgm = 0; 
   var $p11_data_dia = null; 
   var $p11_data_mes = null; 
   var $p11_data_ano = null; 
   var $p11_data = null; 
   var $p11_hora = null; 
   var $p11_fechado = 'f'; 
   var $p11_processado = 'f'; 
   var $p11_contato = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 p11_codigo = int4 = Código 
                 p11_numcgm = int4 = Escritório Contábil 
                 p11_data = date = Data da inclusão 
                 p11_hora = varchar(5) = Hora da inclusão 
                 p11_fechado = bool = Lista concluída 
                 p11_processado = bool = Lista processada 
                 p11_contato = varchar(40) = Contato 
                 ";
   //funcao construtor da classe 
   function cl_listainscrcab() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("listainscrcab"); 
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
       $this->p11_codigo = ($this->p11_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["p11_codigo"]:$this->p11_codigo);
       $this->p11_numcgm = ($this->p11_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["p11_numcgm"]:$this->p11_numcgm);
       if($this->p11_data == ""){
         $this->p11_data_dia = ($this->p11_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["p11_data_dia"]:$this->p11_data_dia);
         $this->p11_data_mes = ($this->p11_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["p11_data_mes"]:$this->p11_data_mes);
         $this->p11_data_ano = ($this->p11_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["p11_data_ano"]:$this->p11_data_ano);
         if($this->p11_data_dia != ""){
            $this->p11_data = $this->p11_data_ano."-".$this->p11_data_mes."-".$this->p11_data_dia;
         }
       }
       $this->p11_hora = ($this->p11_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["p11_hora"]:$this->p11_hora);
       $this->p11_fechado = ($this->p11_fechado == "f"?@$GLOBALS["HTTP_POST_VARS"]["p11_fechado"]:$this->p11_fechado);
       $this->p11_processado = ($this->p11_processado == "f"?@$GLOBALS["HTTP_POST_VARS"]["p11_processado"]:$this->p11_processado);
       $this->p11_contato = ($this->p11_contato == ""?@$GLOBALS["HTTP_POST_VARS"]["p11_contato"]:$this->p11_contato);
     }else{
       $this->p11_codigo = ($this->p11_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["p11_codigo"]:$this->p11_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($p11_codigo){ 
      $this->atualizacampos();
     if($this->p11_numcgm == null ){ 
       $this->erro_sql = " Campo Escritório Contábil nao Informado.";
       $this->erro_campo = "p11_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p11_data == null ){ 
       $this->erro_sql = " Campo Data da inclusão nao Informado.";
       $this->erro_campo = "p11_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p11_hora == null ){ 
       $this->erro_sql = " Campo Hora da inclusão nao Informado.";
       $this->erro_campo = "p11_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p11_fechado == null ){ 
       $this->erro_sql = " Campo Lista concluída nao Informado.";
       $this->erro_campo = "p11_fechado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p11_processado == null ){ 
       $this->erro_sql = " Campo Lista processada nao Informado.";
       $this->erro_campo = "p11_processado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p11_contato == null ){ 
       $this->erro_sql = " Campo Contato nao Informado.";
       $this->erro_campo = "p11_contato";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($p11_codigo == "" || $p11_codigo == null ){
       $result = db_query("select nextval('listainscrcab_p11_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: listainscrcab_p11_codigo_seq do campo: p11_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->p11_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from listainscrcab_p11_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $p11_codigo)){
         $this->erro_sql = " Campo p11_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->p11_codigo = $p11_codigo; 
       }
     }
     if(($this->p11_codigo == null) || ($this->p11_codigo == "") ){ 
       $this->erro_sql = " Campo p11_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into listainscrcab(
                                       p11_codigo 
                                      ,p11_numcgm 
                                      ,p11_data 
                                      ,p11_hora 
                                      ,p11_fechado 
                                      ,p11_processado 
                                      ,p11_contato 
                       )
                values (
                                $this->p11_codigo 
                               ,$this->p11_numcgm 
                               ,".($this->p11_data == "null" || $this->p11_data == ""?"null":"'".$this->p11_data."'")." 
                               ,'$this->p11_hora' 
                               ,'$this->p11_fechado' 
                               ,'$this->p11_processado' 
                               ,'$this->p11_contato' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "cabeçalho da lista de inscrições dos escritorios ($this->p11_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "cabeçalho da lista de inscrições dos escritorios já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "cabeçalho da lista de inscrições dos escritorios ($this->p11_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p11_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->p11_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5168,'$this->p11_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,742,5168,'','".AddSlashes(pg_result($resaco,0,'p11_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,742,5169,'','".AddSlashes(pg_result($resaco,0,'p11_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,742,5170,'','".AddSlashes(pg_result($resaco,0,'p11_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,742,5171,'','".AddSlashes(pg_result($resaco,0,'p11_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,742,5172,'','".AddSlashes(pg_result($resaco,0,'p11_fechado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,742,5173,'','".AddSlashes(pg_result($resaco,0,'p11_processado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,742,5174,'','".AddSlashes(pg_result($resaco,0,'p11_contato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($p11_codigo=null) { 
      $this->atualizacampos();
     $sql = " update listainscrcab set ";
     $virgula = "";
     if(trim($this->p11_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p11_codigo"])){ 
       $sql  .= $virgula." p11_codigo = $this->p11_codigo ";
       $virgula = ",";
       if(trim($this->p11_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "p11_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p11_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p11_numcgm"])){ 
       $sql  .= $virgula." p11_numcgm = $this->p11_numcgm ";
       $virgula = ",";
       if(trim($this->p11_numcgm) == null ){ 
         $this->erro_sql = " Campo Escritório Contábil nao Informado.";
         $this->erro_campo = "p11_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p11_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p11_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["p11_data_dia"] !="") ){ 
       $sql  .= $virgula." p11_data = '$this->p11_data' ";
       $virgula = ",";
       if(trim($this->p11_data) == null ){ 
         $this->erro_sql = " Campo Data da inclusão nao Informado.";
         $this->erro_campo = "p11_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["p11_data_dia"])){ 
         $sql  .= $virgula." p11_data = null ";
         $virgula = ",";
         if(trim($this->p11_data) == null ){ 
           $this->erro_sql = " Campo Data da inclusão nao Informado.";
           $this->erro_campo = "p11_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->p11_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p11_hora"])){ 
       $sql  .= $virgula." p11_hora = '$this->p11_hora' ";
       $virgula = ",";
       if(trim($this->p11_hora) == null ){ 
         $this->erro_sql = " Campo Hora da inclusão nao Informado.";
         $this->erro_campo = "p11_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p11_fechado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p11_fechado"])){ 
       $sql  .= $virgula." p11_fechado = '$this->p11_fechado' ";
       $virgula = ",";
       if(trim($this->p11_fechado) == null ){ 
         $this->erro_sql = " Campo Lista concluída nao Informado.";
         $this->erro_campo = "p11_fechado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p11_processado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p11_processado"])){ 
       $sql  .= $virgula." p11_processado = '$this->p11_processado' ";
       $virgula = ",";
       if(trim($this->p11_processado) == null ){ 
         $this->erro_sql = " Campo Lista processada nao Informado.";
         $this->erro_campo = "p11_processado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p11_contato)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p11_contato"])){ 
       $sql  .= $virgula." p11_contato = '$this->p11_contato' ";
       $virgula = ",";
       if(trim($this->p11_contato) == null ){ 
         $this->erro_sql = " Campo Contato nao Informado.";
         $this->erro_campo = "p11_contato";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($p11_codigo!=null){
       $sql .= " p11_codigo = $this->p11_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->p11_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5168,'$this->p11_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p11_codigo"]))
           $resac = db_query("insert into db_acount values($acount,742,5168,'".AddSlashes(pg_result($resaco,$conresaco,'p11_codigo'))."','$this->p11_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p11_numcgm"]))
           $resac = db_query("insert into db_acount values($acount,742,5169,'".AddSlashes(pg_result($resaco,$conresaco,'p11_numcgm'))."','$this->p11_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p11_data"]))
           $resac = db_query("insert into db_acount values($acount,742,5170,'".AddSlashes(pg_result($resaco,$conresaco,'p11_data'))."','$this->p11_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p11_hora"]))
           $resac = db_query("insert into db_acount values($acount,742,5171,'".AddSlashes(pg_result($resaco,$conresaco,'p11_hora'))."','$this->p11_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p11_fechado"]))
           $resac = db_query("insert into db_acount values($acount,742,5172,'".AddSlashes(pg_result($resaco,$conresaco,'p11_fechado'))."','$this->p11_fechado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p11_processado"]))
           $resac = db_query("insert into db_acount values($acount,742,5173,'".AddSlashes(pg_result($resaco,$conresaco,'p11_processado'))."','$this->p11_processado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p11_contato"]))
           $resac = db_query("insert into db_acount values($acount,742,5174,'".AddSlashes(pg_result($resaco,$conresaco,'p11_contato'))."','$this->p11_contato',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cabeçalho da lista de inscrições dos escritorios nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->p11_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cabeçalho da lista de inscrições dos escritorios nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->p11_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p11_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($p11_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($p11_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5168,'$p11_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,742,5168,'','".AddSlashes(pg_result($resaco,$iresaco,'p11_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,742,5169,'','".AddSlashes(pg_result($resaco,$iresaco,'p11_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,742,5170,'','".AddSlashes(pg_result($resaco,$iresaco,'p11_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,742,5171,'','".AddSlashes(pg_result($resaco,$iresaco,'p11_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,742,5172,'','".AddSlashes(pg_result($resaco,$iresaco,'p11_fechado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,742,5173,'','".AddSlashes(pg_result($resaco,$iresaco,'p11_processado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,742,5174,'','".AddSlashes(pg_result($resaco,$iresaco,'p11_contato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from listainscrcab
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($p11_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " p11_codigo = $p11_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cabeçalho da lista de inscrições dos escritorios nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$p11_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cabeçalho da lista de inscrições dos escritorios nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$p11_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$p11_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:listainscrcab";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $p11_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from listainscrcab ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = listainscrcab.p11_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($p11_codigo!=null ){
         $sql2 .= " where listainscrcab.p11_codigo = $p11_codigo "; 
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
   function sql_query_file ( $p11_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from listainscrcab ";
     $sql2 = "";
     if($dbwhere==""){
       if($p11_codigo!=null ){
         $sql2 .= " where listainscrcab.p11_codigo = $p11_codigo "; 
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