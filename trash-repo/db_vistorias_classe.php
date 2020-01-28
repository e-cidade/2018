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

//MODULO: fiscal
//CLASSE DA ENTIDADE vistorias
class cl_vistorias { 
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
   var $y70_codvist = 0; 
   var $y70_data_dia = null; 
   var $y70_data_mes = null; 
   var $y70_data_ano = null; 
   var $y70_data = null; 
   var $y70_hora = null; 
   var $y70_obs = null; 
   var $y70_contato = null; 
   var $y70_tipovist = 0; 
   var $y70_ultandam = 0; 
   var $y70_id_usuario = 0; 
   var $y70_coddepto = 0; 
   var $y70_numbloco = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 y70_codvist = int4 = Código da Vistoria 
                 y70_data = date = Data da Vistorias 
                 y70_hora = char(5) = Hora da Vistoria 
                 y70_obs = text = Observação da Vistoria 
                 y70_contato = varchar(50) = Nome do Contato da Vistoria 
                 y70_tipovist = int4 = Código do Tipo 
                 y70_ultandam = int8 = Codigo do Último Andamento Gerado 
                 y70_id_usuario = int4 = Cod. Usuário 
                 y70_coddepto = int4 = Código 
                 y70_numbloco = varchar(20) = Número do bloco 
                 ";
   //funcao construtor da classe 
   function cl_vistorias() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("vistorias"); 
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
       $this->y70_codvist = ($this->y70_codvist == ""?@$GLOBALS["HTTP_POST_VARS"]["y70_codvist"]:$this->y70_codvist);
       if($this->y70_data == ""){
         $this->y70_data_dia = ($this->y70_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["y70_data_dia"]:$this->y70_data_dia);
         $this->y70_data_mes = ($this->y70_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["y70_data_mes"]:$this->y70_data_mes);
         $this->y70_data_ano = ($this->y70_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["y70_data_ano"]:$this->y70_data_ano);
         if($this->y70_data_dia != ""){
            $this->y70_data = $this->y70_data_ano."-".$this->y70_data_mes."-".$this->y70_data_dia;
         }
       }
       $this->y70_hora = ($this->y70_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["y70_hora"]:$this->y70_hora);
       $this->y70_obs = ($this->y70_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["y70_obs"]:$this->y70_obs);
       $this->y70_contato = ($this->y70_contato == ""?@$GLOBALS["HTTP_POST_VARS"]["y70_contato"]:$this->y70_contato);
       $this->y70_tipovist = ($this->y70_tipovist == ""?@$GLOBALS["HTTP_POST_VARS"]["y70_tipovist"]:$this->y70_tipovist);
       $this->y70_ultandam = ($this->y70_ultandam == ""?@$GLOBALS["HTTP_POST_VARS"]["y70_ultandam"]:$this->y70_ultandam);
       $this->y70_id_usuario = ($this->y70_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["y70_id_usuario"]:$this->y70_id_usuario);
       $this->y70_coddepto = ($this->y70_coddepto == ""?@$GLOBALS["HTTP_POST_VARS"]["y70_coddepto"]:$this->y70_coddepto);
       $this->y70_numbloco = ($this->y70_numbloco == ""?@$GLOBALS["HTTP_POST_VARS"]["y70_numbloco"]:$this->y70_numbloco);
     }else{
       $this->y70_codvist = ($this->y70_codvist == ""?@$GLOBALS["HTTP_POST_VARS"]["y70_codvist"]:$this->y70_codvist);
     }
   }
   // funcao para inclusao
   function incluir ($y70_codvist){ 
      $this->atualizacampos();
     if($this->y70_data == null ){ 
       $this->erro_sql = " Campo Data da Vistorias nao Informado.";
       $this->erro_campo = "y70_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y70_hora == null ){ 
       $this->erro_sql = " Campo Hora da Vistoria nao Informado.";
       $this->erro_campo = "y70_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y70_contato == null ){ 
       $this->erro_sql = " Campo Nome do Contato da Vistoria nao Informado.";
       $this->erro_campo = "y70_contato";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y70_tipovist == null ){ 
       $this->erro_sql = " Campo Código do Tipo nao Informado.";
       $this->erro_campo = "y70_tipovist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y70_ultandam == null ){ 
       $this->erro_sql = " Campo Codigo do Último Andamento Gerado nao Informado.";
       $this->erro_campo = "y70_ultandam";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y70_id_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "y70_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y70_coddepto == null ){ 
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "y70_coddepto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y70_numbloco == null ){ 
       $this->erro_sql = " Campo Número do bloco nao Informado.";
       $this->erro_campo = "y70_numbloco";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($y70_codvist == "" || $y70_codvist == null ){
       $result = @pg_query("select nextval('vistorias_y70_codvist_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: vistorias_y70_codvist_seq do campo: y70_codvist"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->y70_codvist = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from vistorias_y70_codvist_seq");
       if(($result != false) && (pg_result($result,0,0) < $y70_codvist)){
         $this->erro_sql = " Campo y70_codvist maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->y70_codvist = $y70_codvist; 
       }
     }
     if(($this->y70_codvist == null) || ($this->y70_codvist == "") ){ 
       $this->erro_sql = " Campo y70_codvist nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into vistorias(
                                       y70_codvist 
                                      ,y70_data 
                                      ,y70_hora 
                                      ,y70_obs 
                                      ,y70_contato 
                                      ,y70_tipovist 
                                      ,y70_ultandam 
                                      ,y70_id_usuario 
                                      ,y70_coddepto 
                                      ,y70_numbloco 
                       )
                values (
                                $this->y70_codvist 
                               ,".($this->y70_data == "null" || $this->y70_data == ""?"null":"'".$this->y70_data."'")." 
                               ,'$this->y70_hora' 
                               ,'$this->y70_obs' 
                               ,'$this->y70_contato' 
                               ,$this->y70_tipovist 
                               ,$this->y70_ultandam 
                               ,$this->y70_id_usuario 
                               ,$this->y70_coddepto 
                               ,'$this->y70_numbloco' 
                      )";
     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "vistorias ($this->y70_codvist) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "vistorias já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "vistorias ($this->y70_codvist) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y70_codvist;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $resaco = $this->sql_record($this->sql_query_file($this->y70_codvist));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,4901,'$this->y70_codvist','I')");
       $resac = pg_query("insert into db_acount values($acount,669,4901,'','".AddSlashes(pg_result($resaco,0,'y70_codvist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,669,4902,'','".AddSlashes(pg_result($resaco,0,'y70_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,669,4903,'','".AddSlashes(pg_result($resaco,0,'y70_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,669,4904,'','".AddSlashes(pg_result($resaco,0,'y70_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,669,4905,'','".AddSlashes(pg_result($resaco,0,'y70_contato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,669,4906,'','".AddSlashes(pg_result($resaco,0,'y70_tipovist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,669,5065,'','".AddSlashes(pg_result($resaco,0,'y70_ultandam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,669,5066,'','".AddSlashes(pg_result($resaco,0,'y70_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,669,5073,'','".AddSlashes(pg_result($resaco,0,'y70_coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,669,5087,'','".AddSlashes(pg_result($resaco,0,'y70_numbloco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($y70_codvist=null) { 
      $this->atualizacampos();
     $sql = " update vistorias set ";
     $virgula = "";
     if(trim($this->y70_codvist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y70_codvist"])){ 
       $sql  .= $virgula." y70_codvist = $this->y70_codvist ";
       $virgula = ",";
       if(trim($this->y70_codvist) == null ){ 
         $this->erro_sql = " Campo Código da Vistoria nao Informado.";
         $this->erro_campo = "y70_codvist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y70_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y70_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["y70_data_dia"] !="") ){ 
       $sql  .= $virgula." y70_data = '$this->y70_data' ";
       $virgula = ",";
       if(trim($this->y70_data) == null ){ 
         $this->erro_sql = " Campo Data da Vistorias nao Informado.";
         $this->erro_campo = "y70_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["y70_data_dia"])){ 
         $sql  .= $virgula." y70_data = null ";
         $virgula = ",";
         if(trim($this->y70_data) == null ){ 
           $this->erro_sql = " Campo Data da Vistorias nao Informado.";
           $this->erro_campo = "y70_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->y70_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y70_hora"])){ 
       $sql  .= $virgula." y70_hora = '$this->y70_hora' ";
       $virgula = ",";
       if(trim($this->y70_hora) == null ){ 
         $this->erro_sql = " Campo Hora da Vistoria nao Informado.";
         $this->erro_campo = "y70_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y70_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y70_obs"])){ 
       $sql  .= $virgula." y70_obs = '$this->y70_obs' ";
       $virgula = ",";
     }
     if(trim($this->y70_contato)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y70_contato"])){ 
       $sql  .= $virgula." y70_contato = '$this->y70_contato' ";
       $virgula = ",";
       if(trim($this->y70_contato) == null ){ 
         $this->erro_sql = " Campo Nome do Contato da Vistoria nao Informado.";
         $this->erro_campo = "y70_contato";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y70_tipovist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y70_tipovist"])){ 
       $sql  .= $virgula." y70_tipovist = $this->y70_tipovist ";
       $virgula = ",";
       if(trim($this->y70_tipovist) == null ){ 
         $this->erro_sql = " Campo Código do Tipo nao Informado.";
         $this->erro_campo = "y70_tipovist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y70_ultandam)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y70_ultandam"])){ 
       $sql  .= $virgula." y70_ultandam = $this->y70_ultandam ";
       $virgula = ",";
       if(trim($this->y70_ultandam) == null ){ 
         $this->erro_sql = " Campo Codigo do Último Andamento Gerado nao Informado.";
         $this->erro_campo = "y70_ultandam";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y70_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y70_id_usuario"])){ 
       $sql  .= $virgula." y70_id_usuario = $this->y70_id_usuario ";
       $virgula = ",";
       if(trim($this->y70_id_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "y70_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y70_coddepto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y70_coddepto"])){ 
       $sql  .= $virgula." y70_coddepto = $this->y70_coddepto ";
       $virgula = ",";
       if(trim($this->y70_coddepto) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "y70_coddepto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y70_numbloco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y70_numbloco"])){ 
       $sql  .= $virgula." y70_numbloco = '$this->y70_numbloco' ";
       $virgula = ",";
       if(trim($this->y70_numbloco) == null ){ 
         $this->erro_sql = " Campo Número do bloco nao Informado.";
         $this->erro_campo = "y70_numbloco";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where  y70_codvist = $this->y70_codvist
";
     $resaco = $this->sql_record($this->sql_query_file($this->y70_codvist));
     if($this->numrows>0){       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,4901,'$this->y70_codvist','A')");
       if(isset($GLOBALS["HTTP_POST_VARS"]["y70_codvist"]))
         $resac = pg_query("insert into db_acount values($acount,669,4901,'".AddSlashes(pg_result($resaco,0,'y70_codvist'))."','$this->y70_codvist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["y70_data"]))
         $resac = pg_query("insert into db_acount values($acount,669,4902,'".AddSlashes(pg_result($resaco,0,'y70_data'))."','$this->y70_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["y70_hora"]))
         $resac = pg_query("insert into db_acount values($acount,669,4903,'".AddSlashes(pg_result($resaco,0,'y70_hora'))."','$this->y70_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["y70_obs"]))
         $resac = pg_query("insert into db_acount values($acount,669,4904,'".AddSlashes(pg_result($resaco,0,'y70_obs'))."','$this->y70_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["y70_contato"]))
         $resac = pg_query("insert into db_acount values($acount,669,4905,'".AddSlashes(pg_result($resaco,0,'y70_contato'))."','$this->y70_contato',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["y70_tipovist"]))
         $resac = pg_query("insert into db_acount values($acount,669,4906,'".AddSlashes(pg_result($resaco,0,'y70_tipovist'))."','$this->y70_tipovist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["y70_ultandam"]))
         $resac = pg_query("insert into db_acount values($acount,669,5065,'".AddSlashes(pg_result($resaco,0,'y70_ultandam'))."','$this->y70_ultandam',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["y70_id_usuario"]))
         $resac = pg_query("insert into db_acount values($acount,669,5066,'".AddSlashes(pg_result($resaco,0,'y70_id_usuario'))."','$this->y70_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["y70_coddepto"]))
         $resac = pg_query("insert into db_acount values($acount,669,5073,'".AddSlashes(pg_result($resaco,0,'y70_coddepto'))."','$this->y70_coddepto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["y70_numbloco"]))
         $resac = pg_query("insert into db_acount values($acount,669,5087,'".AddSlashes(pg_result($resaco,0,'y70_numbloco'))."','$this->y70_numbloco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "vistorias nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->y70_codvist;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "vistorias nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->y70_codvist;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y70_codvist;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($y70_codvist=null) { 
     $resaco = $this->sql_record($this->sql_query_file($y70_codvist));
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,4901,'$this->y70_codvist','E')");
         $resac = pg_query("insert into db_acount values($acount,669,4901,'','".AddSlashes(pg_result($resaco,$iresaco,'y70_codvist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,669,4902,'','".AddSlashes(pg_result($resaco,$iresaco,'y70_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,669,4903,'','".AddSlashes(pg_result($resaco,$iresaco,'y70_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,669,4904,'','".AddSlashes(pg_result($resaco,$iresaco,'y70_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,669,4905,'','".AddSlashes(pg_result($resaco,$iresaco,'y70_contato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,669,4906,'','".AddSlashes(pg_result($resaco,$iresaco,'y70_tipovist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,669,5065,'','".AddSlashes(pg_result($resaco,$iresaco,'y70_ultandam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,669,5066,'','".AddSlashes(pg_result($resaco,$iresaco,'y70_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,669,5073,'','".AddSlashes(pg_result($resaco,$iresaco,'y70_coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,669,5087,'','".AddSlashes(pg_result($resaco,$iresaco,'y70_numbloco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from vistorias
                    where ";
     $sql2 = "";
      if($y70_codvist != ""){
      if($sql2!=""){
        $sql2 .= " and ";
      }
      $sql2 .= " y70_codvist = $y70_codvist ";
}
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "vistorias nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$y70_codvist;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "vistorias nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$y70_codvist;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$y70_codvist;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:vistorias";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $y70_codvist=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vistorias ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = vistorias.y70_id_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = vistorias.y70_coddepto";
     $sql .= "      inner join fandam  on  fandam.y39_codandam = vistorias.y70_ultandam";
     $sql .= "      inner join tipovistorias  on  tipovistorias.y77_codtipo = vistorias.y70_tipovist";
     $sql .= "      inner join db_usuarios x  on  x.id_usuario = fandam.y39_id_usuario";
     $sql .= "      inner join tipoandam  on  tipoandam.y41_codtipo = fandam.y39_codtipo";
     $sql .= "      inner join db_depart  as a on   a.coddepto = tipovistorias.y77_coddepto";
     $sql .= "      inner join tipoandam  as b on   b.y41_codtipo = tipovistorias.y77_tipoandam";
     $sql2 = "";
     if($dbwhere==""){
       if($y70_codvist!=null ){
         $sql2 .= " where vistorias.y70_codvist = $y70_codvist "; 
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
   function sql_query_file ( $y70_codvist=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vistorias ";
     $sql2 = "";
     if($dbwhere==""){
       if($y70_codvist!=null ){
         $sql2 .= " where vistorias.y70_codvist = $y70_codvist "; 
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
   function sql_calculo ( $y70_codvist=""){
   $result =  pg_exec("select fc_vistorias($y70_codvist)");
   return $result;
  }
   function sql_querycgm ( $y70_codvist=null){ 
     $sql = "select ";
     $sql .= " case when q02_numcgm is not null then q02_numcgm else 
     			(case when j01_numcgm is not null then j01_numcgm else 
				(case when y73_numcgm is not null then y73_numcgm else
					(case when y80_numcgm is not null then y80_numcgm else q02_numcgm 
					end)
				end)
			end)
		end as z01_numcgm from vistorias left outer join vistinscr on y71_codvist = y70_codvist left join vistmatric on y72_codvist = y70_codvist left join vistcgm on y73_codvist = y70_codvist left join vistsanitario on y74_codvist = y70_codvist left join issbase on y71_inscr = q02_inscr left join iptubase on j01_matric = y72_matric left join sanitario on y80_codsani = y74_codsani 
		";
     $sql2 = "";
     if($y70_codvist!=null ){
       $sql2 .= " where vistorias.y70_codvist = $y70_codvist "; 
     } 
     $sql .= $sql2;
     return $sql;
  }
   function sql_querylocal ( $y70_codvist=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vistorias ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = vistorias.y70_id_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = vistorias.y70_coddepto";
     $sql .= "      inner join fandam  on  fandam.y39_codandam = vistorias.y70_ultandam";
     $sql .= "      inner join tipovistorias  on  tipovistorias.y77_codtipo = vistorias.y70_tipovist";
     $sql .= "      inner join db_usuarios x  on  x.id_usuario = fandam.y39_id_usuario";
     $sql .= "      inner join tipoandam  on  tipoandam.y41_codtipo = fandam.y39_codtipo";
     $sql .= "      inner join db_depart  as a on   a.coddepto = tipovistorias.y77_coddepto";
     $sql .= "      inner join tipoandam  as b on   b.y41_codtipo = tipovistorias.y77_tipoandam";
     $sql .= "      inner join vistlocal on vistlocal.y10_codvist = vistorias.y70_codvist"; 
     $sql2 = "";
     if($dbwhere==""){
       if($y70_codvist!=null ){
         $sql2 .= " where vistorias.y70_codvist = $y70_codvist "; 
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