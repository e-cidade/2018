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

//MODULO: Farmácia
//CLASSE DA ENTIDADE far_retirada
class cl_tmp_far_retirada { 
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
   var $fa04_i_codigo = 0; 
   var $fa04_c_numeroreceita = null; 
   var $fa04_d_dtvalidade_dia = null; 
   var $fa04_d_dtvalidade_mes = null; 
   var $fa04_d_dtvalidade_ano = null; 
   var $fa04_d_dtvalidade = null; 
   var $fa04_i_unidades = 0; 
   var $fa04_i_cgsund = 0; 
   var $fa04_i_tiporeceita = 0; 
   var $fa04_i_dbusuario = 0; 
   var $fa04_d_data_dia = null; 
   var $fa04_d_data_mes = null; 
   var $fa04_d_data_ano = null; 
   var $fa04_d_data = null; 
   var $fa04_i_profissional = 0;
   //Nome das tabelas temporárias    
   var $tmp_far_retirada = null;
   var $tmp_far_retiradaitens = null;
   var $tmp_far_retiradarequisitante = null;

   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 fa04_i_codigo = int4 = Código 
                 fa04_c_numeroreceita = char(10) = Número receita 
                 fa04_d_dtvalidade = date = Data validade 
                 fa04_i_unidades = int4 = Unidades 
                 fa04_i_cgsund = int4 = Cgsund 
                 fa04_i_tiporeceita = int4 = Tipo receita 
                 fa04_i_dbusuario = int4 = Usuario 
                 fa04_d_data = date = Data 
                 fa04_i_profissional = int4 = Profissional 
                
                 ";
   //funcao construtor da classe 
   function cl_tmp_far_retirada() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tmp_far_retirada"); 
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
       $this->fa04_i_codigo = ($this->fa04_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa04_i_codigo"]:$this->fa04_i_codigo);
       $this->fa04_c_numeroreceita = ($this->fa04_c_numeroreceita == ""?@$GLOBALS["HTTP_POST_VARS"]["fa04_c_numeroreceita"]:$this->fa04_c_numeroreceita);
       if($this->fa04_d_dtvalidade == ""){
         $this->fa04_d_dtvalidade_dia = ($this->fa04_d_dtvalidade_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["fa04_d_dtvalidade_dia"]:$this->fa04_d_dtvalidade_dia);
         $this->fa04_d_dtvalidade_mes = ($this->fa04_d_dtvalidade_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["fa04_d_dtvalidade_mes"]:$this->fa04_d_dtvalidade_mes);
         $this->fa04_d_dtvalidade_ano = ($this->fa04_d_dtvalidade_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["fa04_d_dtvalidade_ano"]:$this->fa04_d_dtvalidade_ano);
         if($this->fa04_d_dtvalidade_dia != ""){
            $this->fa04_d_dtvalidade = $this->fa04_d_dtvalidade_ano."-".$this->fa04_d_dtvalidade_mes."-".$this->fa04_d_dtvalidade_dia;
         }
       }
       $this->fa04_i_unidades = ($this->fa04_i_unidades == ""?@$GLOBALS["HTTP_POST_VARS"]["fa04_i_unidades"]:$this->fa04_i_unidades);
       $this->fa04_i_cgsund = ($this->fa04_i_cgsund == ""?@$GLOBALS["HTTP_POST_VARS"]["fa04_i_cgsund"]:$this->fa04_i_cgsund);
       $this->fa04_i_tiporeceita = ($this->fa04_i_tiporeceita == ""?@$GLOBALS["HTTP_POST_VARS"]["fa04_i_tiporeceita"]:$this->fa04_i_tiporeceita);
       $this->fa04_i_dbusuario = ($this->fa04_i_dbusuario == ""?@$GLOBALS["HTTP_POST_VARS"]["fa04_i_dbusuario"]:$this->fa04_i_dbusuario);
       if($this->fa04_d_data == ""){
         $this->fa04_d_data_dia = ($this->fa04_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["fa04_d_data_dia"]:$this->fa04_d_data_dia);
         $this->fa04_d_data_mes = ($this->fa04_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["fa04_d_data_mes"]:$this->fa04_d_data_mes);
         $this->fa04_d_data_ano = ($this->fa04_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["fa04_d_data_ano"]:$this->fa04_d_data_ano);
         if($this->fa04_d_data_dia != ""){
            $this->fa04_d_data = $this->fa04_d_data_ano."-".$this->fa04_d_data_mes."-".$this->fa04_d_data_dia;
         }
       }
       $this->fa04_i_profissional = ($this->fa04_i_profissional == ""?@$GLOBALS["HTTP_POST_VARS"]["fa04_i_profissional"]:$this->fa04_i_profissional);
   
     }else{
       $this->fa04_i_codigo = ($this->fa04_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa04_i_codigo"]:$this->fa04_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($fa04_i_codigo){ 
      $this->atualizacampos();
     /*if($this->fa04_d_dtvalidade == null ){ 
       $this->erro_sql = " Campo Data validade nao Informado.";
       $this->erro_campo = "fa04_d_dtvalidade_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }*/
     if($this->fa04_i_unidades == null ){ 
       $this->erro_sql = " Campo Unidades nao Informado.";
       $this->erro_campo = "fa04_i_unidades";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa04_i_cgsund == null ){ 
       $this->erro_sql = " Campo Cgsund nao Informado.";
       $this->erro_campo = "fa04_i_cgsund";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa04_i_tiporeceita == null ){ 
       $this->erro_sql = " Campo Tipo receita nao Informado.";
       $this->erro_campo = "fa04_i_tiporeceita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa04_i_dbusuario == null ){ 
       $this->erro_sql = " Campo Usuario nao Informado.";
       $this->erro_campo = "fa04_i_dbusuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     /*if($this->fa04_d_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "fa04_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }*/
     if($this->fa04_i_profissional == null ){ 
      $this->fa04_i_profissional="null";
     }
     
     if($fa04_i_codigo == "" || $fa04_i_codigo == null ){
       $result = @pg_query("select nextval('farretirada_fa04_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: farretirada_fa04_i_codigo_seq do campo: fa04_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->fa04_i_codigo = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from farretirada_fa04_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $fa04_i_codigo)){
         $this->erro_sql = " Campo fa04_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->fa04_i_codigo = $fa04_i_codigo; 
       }
     }
     if(($this->fa04_i_codigo == null) || ($this->fa04_i_codigo == "") ){ 
       $this->erro_sql = " Campo fa04_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into ".$this->tmp_far_retirada."(
                                       fa04_i_codigo 
                                      ,fa04_c_numeroreceita 
                                      ,fa04_d_dtvalidade 
                                      ,fa04_i_unidades 
                                      ,fa04_i_cgsund 
                                      ,fa04_i_tiporeceita 
                                      ,fa04_i_dbusuario 
                                      ,fa04_d_data 
                                      ,fa04_i_profissional 
                                     
                       )
                values (
                                $this->fa04_i_codigo 
                               ,'$this->fa04_c_numeroreceita' 
                               ,".($this->fa04_d_dtvalidade == "null" || $this->fa04_d_dtvalidade == ""?"null":"'".$this->fa04_d_dtvalidade."'")." 
                               ,$this->fa04_i_unidades 
                               ,$this->fa04_i_cgsund 
                               ,$this->fa04_i_tiporeceita 
                               ,$this->fa04_i_dbusuario 
                               ,".($this->fa04_d_data == "null" || $this->fa04_d_data == ""?"null":"'".$this->fa04_d_data."'")." 
                               ,$this->fa04_i_profissional 
                               
                      )";

     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "far_retirada ($this->fa04_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "far_retirada já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "far_retirada ($this->fa04_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa04_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->fa04_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,12143,'$this->fa04_i_codigo','I')");
       $resac = pg_query("insert into db_acount values($acount,2106,12143,'','".AddSlashes(pg_result($resaco,0,'fa04_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,2106,12144,'','".AddSlashes(pg_result($resaco,0,'fa04_c_numeroreceita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,2106,12147,'','".AddSlashes(pg_result($resaco,0,'fa04_d_dtvalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,2106,12148,'','".AddSlashes(pg_result($resaco,0,'fa04_i_unidades'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,2106,12149,'','".AddSlashes(pg_result($resaco,0,'fa04_i_cgsund'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,2106,12150,'','".AddSlashes(pg_result($resaco,0,'fa04_i_tiporeceita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,2106,12187,'','".AddSlashes(pg_result($resaco,0,'fa04_i_dbusuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,2106,12186,'','".AddSlashes(pg_result($resaco,0,'fa04_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,2106,12188,'','".AddSlashes(pg_result($resaco,0,'fa04_i_profissional'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($fa04_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update ".$this->tmp_far_retirada." set ";
     $virgula = "";
     if(trim($this->fa04_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa04_i_codigo"])){ 
       $sql  .= $virgula." fa04_i_codigo = $this->fa04_i_codigo ";
       $virgula = ",";
       if(trim($this->fa04_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "fa04_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa04_c_numeroreceita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa04_c_numeroreceita"])){ 
       $sql  .= $virgula." fa04_c_numeroreceita = '$this->fa04_c_numeroreceita' ";
       $virgula = ",";
       if(trim($this->fa04_c_numeroreceita) == null ){ 
         $this->erro_sql = " Campo Número receita nao Informado.";
         $this->erro_campo = "fa04_c_numeroreceita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa04_d_dtvalidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa04_d_dtvalidade_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["fa04_d_dtvalidade_dia"] !="") ){ 
       $sql  .= $virgula." fa04_d_dtvalidade = '$this->fa04_d_dtvalidade' ";
       $virgula = ",";
       /*if(trim($this->fa04_d_dtvalidade) == null ){ 
         $this->erro_sql = " Campo Data validade nao Informado.";
         $this->erro_campo = "fa04_d_dtvalidade_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }*/
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["fa04_d_dtvalidade_dia"])){ 
         $sql  .= $virgula." fa04_d_dtvalidade = null ";
         $virgula = ",";
         if(trim($this->fa04_d_dtvalidade) == null ){ 
           $this->erro_sql = " Campo Data validade nao Informado.";
           $this->erro_campo = "fa04_d_dtvalidade_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->fa04_i_unidades)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa04_i_unidades"])){ 
       $sql  .= $virgula." fa04_i_unidades = $this->fa04_i_unidades ";
       $virgula = ",";
       if(trim($this->fa04_i_unidades) == null ){ 
         $this->erro_sql = " Campo Unidades nao Informado.";
         $this->erro_campo = "fa04_i_unidades";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa04_i_cgsund)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa04_i_cgsund"])){ 
       $sql  .= $virgula." fa04_i_cgsund = $this->fa04_i_cgsund ";
       $virgula = ",";
       if(trim($this->fa04_i_cgsund) == null ){ 
         $this->erro_sql = " Campo Cgsund nao Informado.";
         $this->erro_campo = "fa04_i_cgsund";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa04_i_tiporeceita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa04_i_tiporeceita"])){ 
       $sql  .= $virgula." fa04_i_tiporeceita = $this->fa04_i_tiporeceita ";
       $virgula = ",";
       if(trim($this->fa04_i_tiporeceita) == null ){ 
         $this->erro_sql = " Campo Tipo receita nao Informado.";
         $this->erro_campo = "fa04_i_tiporeceita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa04_i_dbusuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa04_i_dbusuario"])){ 
       $sql  .= $virgula." fa04_i_dbusuario = $this->fa04_i_dbusuario ";
       $virgula = ",";
       if(trim($this->fa04_i_dbusuario) == null ){ 
         $this->erro_sql = " Campo Usuario nao Informado.";
         $this->erro_campo = "fa04_i_dbusuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa04_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa04_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["fa04_d_data_dia"] !="") ){ 
       $sql  .= $virgula." fa04_d_data = '$this->fa04_d_data' ";
       $virgula = ",";
       if(trim($this->fa04_d_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "fa04_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["fa04_d_data_dia"])){ 
         $sql  .= $virgula." fa04_d_data = null ";
         $virgula = ",";
         if(trim($this->fa04_d_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "fa04_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->fa04_i_profissional)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa04_i_profissional"])){ 
       $sql  .= $virgula." fa04_i_profissional = $this->fa04_i_profissional ";
       $virgula = ",";
       if(trim($this->fa04_i_profissional) == null ){ 
         $this->erro_sql = " Campo Profissional nao Informado.";
         $this->erro_campo = "fa04_i_profissional";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     
     $sql .= " where ";
     if($fa04_i_codigo!=null){
       $sql .= " fa04_i_codigo = $this->fa04_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->fa04_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,12143,'$this->fa04_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa04_i_codigo"]))
           $resac = pg_query("insert into db_acount values($acount,2106,12143,'".AddSlashes(pg_result($resaco,$conresaco,'fa04_i_codigo'))."','$this->fa04_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa04_c_numeroreceita"]))
           $resac = pg_query("insert into db_acount values($acount,2106,12144,'".AddSlashes(pg_result($resaco,$conresaco,'fa04_c_numeroreceita'))."','$this->fa04_c_numeroreceita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa04_d_dtvalidade"]))
           $resac = pg_query("insert into db_acount values($acount,2106,12147,'".AddSlashes(pg_result($resaco,$conresaco,'fa04_d_dtvalidade'))."','$this->fa04_d_dtvalidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa04_i_unidades"]))
           $resac = pg_query("insert into db_acount values($acount,2106,12148,'".AddSlashes(pg_result($resaco,$conresaco,'fa04_i_unidades'))."','$this->fa04_i_unidades',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa04_i_cgsund"]))
           $resac = pg_query("insert into db_acount values($acount,2106,12149,'".AddSlashes(pg_result($resaco,$conresaco,'fa04_i_cgsund'))."','$this->fa04_i_cgsund',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa04_i_tiporeceita"]))
           $resac = pg_query("insert into db_acount values($acount,2106,12150,'".AddSlashes(pg_result($resaco,$conresaco,'fa04_i_tiporeceita'))."','$this->fa04_i_tiporeceita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa04_i_dbusuario"]))
           $resac = pg_query("insert into db_acount values($acount,2106,12187,'".AddSlashes(pg_result($resaco,$conresaco,'fa04_i_dbusuario'))."','$this->fa04_i_dbusuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa04_d_data"]))
           $resac = pg_query("insert into db_acount values($acount,2106,12186,'".AddSlashes(pg_result($resaco,$conresaco,'fa04_d_data'))."','$this->fa04_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa04_i_profissional"]))
           $resac = pg_query("insert into db_acount values($acount,2106,12188,'".AddSlashes(pg_result($resaco,$conresaco,'fa04_i_profissional'))."','$this->fa04_i_profissional',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         
       }
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "far_retirada nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa04_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "far_retirada nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa04_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa04_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($fa04_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($fa04_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,12143,'$fa04_i_codigo','E')");
         $resac = pg_query("insert into db_acount values($acount,2106,12143,'','".AddSlashes(pg_result($resaco,$iresaco,'fa04_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,2106,12144,'','".AddSlashes(pg_result($resaco,$iresaco,'fa04_c_numeroreceita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,2106,12147,'','".AddSlashes(pg_result($resaco,$iresaco,'fa04_d_dtvalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,2106,12148,'','".AddSlashes(pg_result($resaco,$iresaco,'fa04_i_unidades'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,2106,12149,'','".AddSlashes(pg_result($resaco,$iresaco,'fa04_i_cgsund'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,2106,12150,'','".AddSlashes(pg_result($resaco,$iresaco,'fa04_i_tiporeceita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,2106,12187,'','".AddSlashes(pg_result($resaco,$iresaco,'fa04_i_dbusuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,2106,12186,'','".AddSlashes(pg_result($resaco,$iresaco,'fa04_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,2106,12188,'','".AddSlashes(pg_result($resaco,$iresaco,'fa04_i_profissional'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");        
       }
     }
     $sql = " delete from ".$this->tmp_far_retirada." 
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($fa04_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " fa04_i_codigo = $fa04_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "far_retirada nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$fa04_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "far_retirada nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$fa04_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$fa04_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:far_retirada";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $fa04_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ".$this->tmp_far_retirada." ";
     $sql .= "      inner join db_usuarios      on  db_usuarios.id_usuario = ".$this->tmp_far_retirada.".fa04_i_dbusuario";
     $sql .= "      inner join far_tiporeceita  on  far_tiporeceita.fa03_i_codigo = ".$this->tmp_far_retirada.".fa04_i_tiporeceita";
     $sql .= "      inner join unidades         on  unidades.sd02_i_codigo = ".$this->tmp_far_retirada.".fa04_i_unidades";
     $sql .= "      left join medicos           on  medicos.sd03_i_codigo = ".$this->tmp_far_retirada.".fa04_i_profissional";
     $sql .= "      inner join cgs_und          on  cgs_und.z01_i_cgsund = ".$this->tmp_far_retirada.".fa04_i_cgsund";
     $sql .= "      left join ".$this->tmp_far_retiradarequisitante." on  ".$this->tmp_far_retiradarequisitante.".fa08_i_retirada = ".$this->tmp_far_retirada.".fa04_i_codigo";
     $sql .= "      left join cgs_und b         on b.z01_i_cgsund= ".$this->tmp_far_retiradarequisitante.".fa08_i_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($fa04_i_codigo!=null ){
         $sql2 .= " where ".$this->tmp_far_retirada.".fa04_i_codigo = $fa04_i_codigo "; 
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
   function sql_query_file ( $fa04_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ".$this->tmp_far_retirada." ";
     $sql2 = "";
     if($dbwhere==""){
       if($fa04_i_codigo!=null ){
         $sql2 .= " where ".$this->tmp_far_retirada.".fa04_i_codigo = $fa04_i_codigo "; 
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